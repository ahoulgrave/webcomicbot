<?php

namespace WebComicBot\WebComic;

use Symfony\Component\DomCrawler\Crawler;

class Explosm extends SimpleWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://feeds.feedburner.com/Explosm';

    /**
     * @inheritdoc
     */
    protected $tag = 'explosm';

    /**
     * @inheritdoc
     */
    public function getEntry(Crawler $item)
    {
        $entry = parent::getEntry($item);
        $url = $entry->getUrl();
        $crawler = new Crawler(file_get_contents($url));
        $img = 'http:' . $crawler->filter('#main-comic')->first()->attr('src');
        $img = explode('?', $img);
        $img = reset($img);
        $entry->setPicture($img);
        return $entry;
    }

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'Explosm';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://explosm.net/';
    }
}
