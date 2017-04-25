<?php
namespace WebComicBot\WebComic;

use Symfony\Component\DomCrawler\Crawler;
use WebComicBot\Entity\Entry;

class TheAkwardYeti extends SimpleWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://theawkwardyeti.com/feed/';

    /**
     * @inheritdoc
     */
    protected $tag = 'theakwardyeti';

    /**
     * @inheritdoc
     */
    protected $itemXPath = '//channel/item';

    public function getEntry(Crawler $item)
    {
        $entry = parent::getEntry($item);
        $crawler = new Crawler(file_get_contents($entry->getUrl()));
        $img = $crawler->filter('#comic img')->first()->attr('src');
        $entry->setPicture($img);
        return $entry;
    }

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'The Akward Yeti';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://theawkwardyeti.com/';
    }
}
