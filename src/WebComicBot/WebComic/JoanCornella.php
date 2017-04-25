<?php
namespace WebComicBot\WebComic;

class JoanCornella extends SimpleWebComic
{
    protected $feedUrl = 'http://cornellajoan.tumblr.com/rss';

    protected $tag = 'joancornella';

    public static function getName()
    {
        return 'Joan Cornellà';
    }

    public static function getWebsite()
    {
        return 'http://cornellajoan.tumblr.com/';
    }
}
