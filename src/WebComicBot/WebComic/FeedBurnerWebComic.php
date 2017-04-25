<?php
namespace WebComicBot\WebComic;

use Symfony\Component\DomCrawler\Crawler;
use WebComicBot\Entity\Entry;

abstract class FeedBurnerWebComic extends SimpleWebComic
{
    /**
     * @inheritdoc
     */
    public function getEntry(Crawler $item)
    {
        /* @var $item Crawler */
        $entry = new Entry($this);
        $title = $item->filterXPath('//title')->html();

        $pubDate = $item->filterXPath('//pubDate')->html();
        $pubDate = \DateTime::createFromFormat('D, d M Y H:i:s O', $pubDate);
        $url = $item->filterXPath('//link')->html();

        $description = $item->filterXPath('//content:encoded')->first()->text();
        $crawler = new Crawler($description);
        $imgSrc = $crawler->filter('img')->first()->attr('src');
        $entry->setPicture($imgSrc);

        $entry->setTitle($title);
        $entry->setPubDate($pubDate);
        $entry->setUrl($url);
        return $entry;
    }
}
