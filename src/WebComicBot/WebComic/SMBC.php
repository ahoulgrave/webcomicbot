<?php
namespace WebComicBot\WebComic;

use Symfony\Component\DomCrawler\Crawler;
use WebComicBot\Entity\Entry;

class SMBC extends AbstractWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://www.smbc-comics.com/rss.php';

    /**
     * @inheritdoc
     */
    protected $tag = 'smbc';

    public function getEntries()
    {
        $xml = $this->getFeedContent();
        $crawler = new Crawler($xml);
        $entries = $crawler->first()->filterXPath('//item')->each(function ($item) {
            /* @var $item Crawler */
            $entry = new Entry($this);
            $title = $item->filterXPath('//title')->html();
            $title = explode('-', $title);
            unset($title[0]);
            $title = join('-', $title);
            $pubDate = $item->filterXPath('//pubDate')->html();
            $pubDate = \DateTime::createFromFormat('D, d M Y H:i:s O', $pubDate);
            $url = $item->filterXPath('//link')->html();

            $description = $item->filterXPath('//description')->first()->text();
            $crawler = new Crawler($description);
            $imgSrc = $crawler->filter('img')->first()->attr('src');
            $entry->setPicture($imgSrc);

            $entry->setTitle($title);
            $entry->setPubDate($pubDate);
            $entry->setUrl($url);
            return $entry;
        });
        return $entries;
    }

    public static function getName()
    {
        return 'SMBC';
    }

    public static function getWebsite()
    {
        // TODO: Implement getWebsite() method.
    }
}
