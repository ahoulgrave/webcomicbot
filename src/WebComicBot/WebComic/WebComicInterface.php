<?php
namespace WebComicBot\WebComic;

use WebComicBot\Entity\Entry;

interface WebComicInterface
{
    /**
     * @return string
     */
    public function getFeedUrl();

    /**
     * @return string
     */
    public function getTag();

    /**
     * @return Entry[]
     */
    public function getEntries();

    /**
     * @return string
     */
    public static function getName();

    /**
     * @return string
     */
    public static function getWebsite();
}
