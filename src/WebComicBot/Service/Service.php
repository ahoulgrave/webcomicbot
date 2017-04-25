<?php
namespace WebComicBot\Service;

use Doctrine\ORM\EntityManager;
use GeckoPackages\Silex\Services\Config\ConfigLoader;
use Knp\Console\ConsoleEvent;
use Knp\Console\ConsoleEvents;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Telegram\Bot\Objects\Update;
use WebComicBot\Entity\User;
use WebComicBot\Exception\DuplicatedWebComicException;
use WebComicBot\WebComic\WebComicInterface;

class Service
{
    /**
     * @var ConfigLoader
     */
    private $configService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Logger
     */
    private $monologService;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var WebComicInterface[]
     */
    private $webComics;

    /**
     * @var string[]
     */
    private $tagList;

    /**
     * Service constructor.
     * @param ConfigLoader $configService
     * @param EventDispatcherInterface $eventDispatcher
     * @param Logger $monologService
     */
    public function __construct(
        ConfigLoader $configService,
        EventDispatcherInterface $eventDispatcher,
        Logger $monologService,
        EntityManager $entityManager
    ) {
        $this->setConfigService($configService);
        $this->setEventDispatcher($eventDispatcher);
        $this->setMonologService($monologService);
        $this->setEntityManager($entityManager);
        $this->setTagList([]);
        $this->setWebComics([]);
    }

    /**
     * @param array $commands
     * @return Service
     */
    public function registerCommands(array $commands)
    {
        $this->getEventDispatcher()->addListener(ConsoleEvents::INIT, function(ConsoleEvent $event) use ($commands) {
            $app = $event->getApplication();
            $app->addCommands($commands);
        });
        return $this;
    }

    public function registerWebComics()
    {
        $comics = $this->getConfigService()->get('comics')['comics'];
        foreach ($comics as $comicClass) {
            $class = sprintf('\WebComicBot\WebComic\%s', $comicClass);
            if (!class_exists($class)) {
                throw new \Exception(sprintf('The class %s does not exists.', $class));
            }
            try {
                $this->addWebComic(new $class);
            } catch (\Exception $exception) {
                $this->getMonologService()->error('Error registering webcomic: ' . $exception->getMessage());
            }
        }
        return $this;
    }

    public function getOrCreateTelegramUser(Update $update, $updateInfo = true)
    {
        $chatId = $update->getMessage()->getChat()->getId();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->select('u')
            ->from('WebComicBot\Entity\User', 'u')
            ->where('u.chatId = :chat_id')
            ->setMaxResults(1)
            ->setParameter('chat_id', $chatId)
            ->getQuery();
        $existingUser = $query->getOneOrNullResult();
        if ($existingUser) {
            $user = $existingUser;
        } else {
            $user = new User();
            $user->setJustCreated(true);
            $user->setChatId($chatId);
        }
        if (!$updateInfo && $existingUser) {
            return $existingUser;
        }
        //  Update/create user info
        $user->setLatestActivity(new \DateTime());
        $user->setFirstName($update->getMessage()->getChat()->getFirstName());
        $user->setLastName($update->getMessage()->getChat()->getLastName());
        $user->setTelegramUserName($update->getMessage()->getChat()->getUsername());
        $em->persist($user);
        $em->flush();
        return $user;
    }

    /**
     * @param $name
     * @return null|string
     */
    public function findWebComicByName($name)
    {
        foreach ($this->getWebComics() as $webComic) {
            if (strtolower($name) == strtolower($webComic::getName())) {
                return get_class($webComic);
            }
        }
        return null;
    }

    /**
     * @return ConfigLoader
     */
    public function getConfigService()
    {
        return $this->configService;
    }

    /**
     * @param ConfigLoader $configService
     */
    public function setConfigService($configService)
    {
        $this->configService = $configService;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return \WebComicBot\WebComic\WebComicInterface[]
     */
    public function getWebComics()
    {
        return $this->webComics;
    }

    /**
     * @param \WebComicBot\WebComic\WebComicInterface[] $webComics
     */
    public function setWebComics($webComics)
    {
        $this->webComics = $webComics;
    }

    /**
     * @param WebComicInterface $webComic
     * @return Service
     * @throws DuplicatedWebComicException
     */
    public function addWebComic(WebComicInterface $webComic)
    {
        if (in_array($webComic->getTag(), $this->getTagList())) {
            throw new DuplicatedWebComicException($webComic);
        }
        $this->addTag($webComic->getTag());
        $this->webComics[] = $webComic;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getTagList()
    {
        return $this->tagList;
    }

    /**
     * @param \string[] $tagList
     */
    public function setTagList($tagList)
    {
        $this->tagList = $tagList;
    }

    /**
     * @param string $tag
     */
    public function addTag($tag)
    {
        $this->tagList[] = $tag;
    }

    /**
     * @return Logger
     */
    public function getMonologService()
    {
        return $this->monologService;
    }

    /**
     * @param Logger $monologService
     */
    public function setMonologService($monologService)
    {
        $this->monologService = $monologService;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
