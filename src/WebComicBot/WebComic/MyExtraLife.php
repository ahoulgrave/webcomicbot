<?php
namespace WebComicBot\WebComic;

class MyExtraLife extends FeedBurnerWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://www.myextralife.com/feed/';

    /**
     * @inheritdoc
     */
    protected $tag = 'myextralife';

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'My Extra Life';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://www.myextralife.com/';
    }
}
