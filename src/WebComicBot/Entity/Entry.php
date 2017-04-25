<?php
namespace WebComicBot\Entity;

use Doctrine\ORM\Mapping as ORM;
use WebComicBot\WebComic\WebComicInterface;

/**
 * Class Entry
 * @ORM\Entity
 * @ORM\Table(name="entry")
 */
class Entry
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="creation_date")
     */
    private $creationDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="pub_date")
     */
    private $pubDate;

    /**
     * @var string
     * @ORM\Column(type="string", name="web_comic", length=255)
     */
    private $webComic;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="title", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", name="url", length=255)
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="string", name="picture", length=255)
     */
    private $picture;

    public function __construct(WebComicInterface $webComic)
    {
        $this->setWebComic(get_class($webComic));
        $this->setCreationDate(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * @param \DateTime $pubDate
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;
    }

    /**
     * @return string
     */
    public function getWebComic()
    {
        return $this->webComic;
    }

    /**
     * @param string $webComic
     */
    public function setWebComic($webComic)
    {
        $this->webComic = $webComic;
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
}
