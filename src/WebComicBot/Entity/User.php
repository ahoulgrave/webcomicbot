<?php
namespace WebComicBot\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="chat_id", type="string", length=255, unique=true)
     */
    private $chatId;

    /**
     * @var string|null
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(name="telegram_user_name", type="string", length=255, nullable=true)
     */
    private $telegramUserName;

    /**
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="latest_activity", type="datetime", nullable=true)
     */
    private $latestActivity;

    /**
     * @var bool
     */
    private $justCreated;

    /**
     * @var string[]
     * @ORM\Column(name="web_comics", type="simple_array", nullable=true)
     */
    private $webComics;

    public function __construct()
    {
        $this->setCreationDate(new \DateTime());
        $this->setJustCreated(false);
        $this->setWebComics([]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @param string $chatId
     */
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getTelegramUserName()
    {
        return $this->telegramUserName;
    }

    /**
     * @param string $telegramUserName
     */
    public function setTelegramUserName($telegramUserName)
    {
        $this->telegramUserName = $telegramUserName;
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
     * @return \DateTime|null
     */
    public function getLatestActivity()
    {
        return $this->latestActivity;
    }

    /**
     * @param \DateTime|null $latestActivity
     */
    public function setLatestActivity($latestActivity)
    {
        $this->latestActivity = $latestActivity;
    }

    /**
     * @return boolean
     */
    public function isJustCreated()
    {
        return $this->justCreated;
    }

    /**
     * @param boolean $justCreated
     */
    public function setJustCreated($justCreated)
    {
        $this->justCreated = $justCreated;
    }

    /**
     * @return \string[]
     */
    public function getWebComics()
    {
        return $this->webComics;
    }

    /**
     * @param \string[] $webComics
     */
    public function setWebComics($webComics)
    {
        $this->webComics = $webComics;
    }

    /**
     * @param string $webComic
     */
    public function addWebComic($webComic)
    {
        if (!in_array($webComic, $this->webComics)) {
            $this->webComics[] = $webComic;
        }
    }

    /**
     * @param $webComic
     * @return bool
     */
    public function removeWebComic($webComic)
    {
        $pos = array_search($webComic, $this->webComics);
        if ($pos !== false) {
            unset($this->webComics[$pos]);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->getFirstName()) {
            return $this->getFirstName();
        } else {
            return $this->getTelegramUserName();
        }
    }
}
