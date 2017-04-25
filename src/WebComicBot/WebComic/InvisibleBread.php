<?php
namespace WebComicBot\WebComic;

class InvisibleBread extends FeedBurnerWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://feeds.feedburner.com/InvisibleBread';

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'Invisible Bread';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://invisiblebread.com/';
    }
}
