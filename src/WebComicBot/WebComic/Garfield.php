<?php
namespace WebComicBot\WebComic;

use Symfony\Component\DomCrawler\Crawler;
use WebComicBot\Entity\Entry;

class Garfield extends AbstractWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'https://garfield.com/comic';

    /**
     * @inheritdoc
     */
    protected $tag = 'garfield';

    /**
     * @inheritdoc
     */
    public function getEntries()
    {
        $content = $this->getFeedContent();
        $crawler = new Crawler($content);
        $imgUrl = $crawler->filter('.comic-display img')->first()->attr('src');
        $date = explode('/', $imgUrl);
        $date = end($date);
        $date = explode('.', $date);
        $date = reset($date);
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
        $entry = new Entry($this);
        $entry->setPubDate($date);
        $entry->setTitle('');
        $entry->setPicture($imgUrl);
        $url = sprintf(
            'https://garfield.com/comic/%s/%s/%s',
            $date->format('Y'),
            $date->format('m'),
            $date->format('d')
        );
        $entry->setUrl($url);
        return [$entry];
    }

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'Garfield';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'https://www.garfield.com/';
    }
}
