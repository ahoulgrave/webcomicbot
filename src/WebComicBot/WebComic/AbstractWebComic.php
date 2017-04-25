<?php
namespace WebComicBot\WebComic;

abstract class AbstractWebComic implements WebComicInterface
{
    /**
     * @inheritdoc
     */
    protected $feedUrl;

    /**
     * @inheritdoc
     */
    protected $tag;

    /**
     * @inheritdoc
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * @inheritdoc
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getFeedContent()
    {
        $content = file_get_contents($this->getFeedUrl());
        return $content;
    }
}
