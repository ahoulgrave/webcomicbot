<?php
namespace WebComicBot\WebComic;

class XKCD extends SimpleWebComic
{
    protected $feedUrl = 'https://xkcd.com/rss.xml';

    protected $tag = 'xkcd';

    public static function getName()
    {
        return 'xkcd';
    }

    public static function getWebsite()
    {
        return 'https://xkcd.com/';
    }
}
