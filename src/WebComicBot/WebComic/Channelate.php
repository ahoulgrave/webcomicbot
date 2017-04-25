<?php

namespace WebComicBot\WebComic;

class Channelate extends FeedBurnerWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://feeds.feedburner.com/Channelate?format=xml';

    /**
     * @inheritdoc
     */
    protected $tag = 'channelate';

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'Channelate';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://www.channelate.com/';
    }
}
