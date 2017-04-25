<?php
namespace WebComicBot\WebComic;

use Symfony\Component\DomCrawler\Crawler;
use WebComicBot\Entity\Entry;

abstract class SimpleWebComic extends AbstractWebComic
{
    /**
     * @var string
     */
    protected $itemXPath = '//item';

    /**
     * @var string
     */
    protected $descriptionXPath = '//description';

    /**
     * @return Entry[]
     */
    public function getEntries()
    {
        $xml = $this->getFeedContent();
        $crawler = new Crawler($xml);
        $entries = [];
        $entryElements = $crawler->filterXPath($this->itemXPath);
        if ($this instanceof TheAkwardYeti) {
            var_dump($entryElements->count());
        }
        try {
            if ($entryElements->count()) {
                $iterator = $entryElements->getIterator();
                for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
                    $item = new Crawler($iterator->current());
                    $entry = $this->getEntry($item);
                    $entries[] = $entry;
                }
            }
        } catch (\Exception $e) {
            fputs(STDOUT, $entryElements->count() . PHP_EOL);
        }
        return $entries;
    }

    /**
     * @param Crawler $item
     * @return Entry
     */
    public function getEntry(Crawler $item)
    {
        /* @var $item Crawler */
        $entry = new Entry($this);
        $title = $item->filterXPath('//title')->html();

        $pubDate = $item->filterXPath('//pubDate')->html();
        $pubDate = \DateTime::createFromFormat('D, d M Y H:i:s O', $pubDate);
        $url = $item->filterXPath('//link')->html();

        $description = $item->filterXPath($this->descriptionXPath)->first()->text();
        $crawler = new Crawler($description);
        $img = $crawler->filter('img');
        if ($img->count()) {
            $imgSrc = $img->first()->attr('src');
            $entry->setPicture($imgSrc);
        }
        $entry->setTitle($title);
        $entry->setPubDate($pubDate);
        $entry->setUrl($url);
        return $entry;
    }
}
