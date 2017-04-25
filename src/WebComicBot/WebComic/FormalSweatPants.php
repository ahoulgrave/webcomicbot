<?php
namespace WebComicBot\WebComic;

class FormalSweatPants extends FeedBurnerWebComic
{
    protected $feedUrl = 'http://formalsweatpants.com/feed/';

    protected $tag = 'formalsweatpants';

    public static function getName()
    {
        return 'Formal Sweat Pants';
    }

    public static function getWebsite()
    {
        return 'http://formalsweatpants.com/';
    }
}
