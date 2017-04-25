<?php
namespace WebComicBot\WebComic;

class AmazingSuperPowers extends FeedBurnerWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://feeds.feedburner.com/amazingsuperpowers?format=xml';

    /**
     * @inheritdoc
     */
    protected $tag = 'invisiblesuperpowers';

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'Amazing Super Powers';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://www.amazingsuperpowers.com/';
    }
}
