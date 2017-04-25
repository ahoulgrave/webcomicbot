<?php
namespace WebComicBot\WebComic;

use Symfony\Component\DomCrawler\Crawler;
use WebComicBot\Entity\Entry;

class PoorlyDrawnLines extends AbstractWebComic
{
    public function __construct()
    {
        $this->feedUrl = 'http://poorlydrawnlines.com/feed/';
        $this->tag = 'poorly_drawn_lines';
    }

    /**
     * @inheritdoc
     */
    public function getEntries()
    {
        $xml = $this->getFeedContent();
        $crawler = new Crawler($xml);
        $entries = $crawler->first()->filterXPath('//item')->each(function ($item) {
            /* @var $item Crawler */
            $entry = new Entry($this);
            $title = $item->filterXPath('//title')->html();
            $pubDate = $item->filterXPath('//pubDate')->html();
            $pubDate = \DateTime::createFromFormat('D, d M Y H:i:s O', $pubDate);
            $url = $item->filterXPath('//guid')->html();

            $description = $item->filterXPath('//content:encoded')->first()->text();
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

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'Poorly Drawn Lines';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://www.poorlydrawnlines.com/';
    }
}
