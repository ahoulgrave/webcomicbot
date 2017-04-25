<?php
namespace WebComicBot\WebComic;

class CommitStrip extends SimpleWebComic
{
    /**
     * @inheritdoc
     */
    protected $feedUrl = 'http://www.commitstrip.com/en/feed/';

    /**
     * @inheritdoc
     */
    protected $tag = 'commitstrip';

    /**
     * @inheritdoc
     */
    protected $descriptionXPath = '//content:encoded';

    /**
     * @inheritdoc
     */
    public static function getName()
    {
        return 'CommitStrip';
    }

    /**
     * @inheritdoc
     */
    public static function getWebsite()
    {
        return 'http://www.commitstrip.com/';
    }
}
