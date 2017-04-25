<?php
namespace WebComicBot\Command;

use Doctrine\ORM\EntityManager;
use Knp\Command\Command;
use Monolog\Logger;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Telegram\Bot\Api;
use WebComicBot\Entity\Entry;
use WebComicBot\Entity\User;
use WebComicBot\Service\Service;

class FetchCommand extends Command
{
    /**
     * @var Service
     */
    private $webComicsService;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Logger
     */
    private $monologService;

    /**
     * @var Api
     */
    private $telegramService;

    public function __construct(
        Service $webComicsService,
        EntityManager $entityManager,
        Logger $monologService,
        Api $telegramService
    ) {
        parent::__construct(null);
        $this->setWebComicsService($webComicsService);
        $this->setEntityManager($entityManager);
        $this->setMonologService($monologService);
        $this->setTelegramService($telegramService);
    }

    protected function configure()
    {
        $this->setName('wcb:fetch')
            ->setDescription('Updates database with webcomics');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getEntityManager();
        $service = $this->getWebComicsService();
        $webComics = $service->getWebComics();
        $output->writeln('Importing webcomics...');
        /* @var $toSend Entry[] */
        $toSend = [];
        /* @var $users User[] */
        $users = $em->createQueryBuilder()
            ->select('u')
            ->from('WebComicBot\Entity\User', 'u')
            ->where($em->getExpressionBuilder()->isNotNull('u.webComics'))
            ->getQuery()
            ->getResult();
        foreach ($webComics as $webComic) {
            try {
                $entries = $webComic->getEntries();
                $logEntry = sprintf(
                    'Found %s results for %s... ',
                    count($entries),
                    get_class($webComic)
                );
                $output->write($logEntry);
                $this->getMonologService()->info($logEntry);
                $importedEntries = 0;
                foreach ($entries as $entry) {
                    $query = $em->createQueryBuilder()
                        ->select('e')
                        ->from('WebComicBot\Entity\Entry', 'e')
                        ->where('e.pubDate = :pubDate')
                        ->andWhere('e.webComic = :webComic')
                        ->setParameters([
                            'pubDate' => $entry->getPubDate(),
                            'webComic' => $entry->getWebComic()
                        ])
                        ->setMaxResults(1)
                        ->getQuery();
                    $existingEntry = $query->getOneOrNullResult();
                    if (!$existingEntry) {
                        $toSend[] = $entry;
                    }
                }
                $sentMessages = 0;
                if (!empty($users) && !empty($toSend)) {
                    $tg = $this->getTelegramService();
                    foreach ($users as $user) {
                        foreach ($toSend as $entry) {
                            $webComic = $entry->getWebComic();
                            if (in_array($webComic, $user->getWebComics())) {
                                $caption = $entry->getTitle() . PHP_EOL . PHP_EOL;
                                $caption.= 'View online: ' . $entry->getUrl();
                                try {
                                    $tg->sendPhoto([
                                        'photo' => $entry->getPicture(),
                                        'caption' => $caption,
                                        'chat_id' => $user->getChatId()
                                    ]);
                                    $sentMessages++;
                                    $importedEntries++;
                                    $em->persist($entry);
                                } catch (\Exception $e) {
                                    $this->getMonologService()->error('Error sending image that is not image: ' . $e->getMessage());
                                }
                            }
                        }
                    }
                }
                $logEntry = sprintf(
                    '%s imported.',
                    $importedEntries
                );
                $this->getMonologService()->info($logEntry);
                $output->writeln($logEntry);
                $logEntry = sprintf(
                    '%s: %s messages sent',
                    $webComic::getName(),
                    $sentMessages
                );
                $this->getMonologService()->info($logEntry);
                $output->writeln($logEntry);
                $toSend = [];
                $em->flush();
            } catch (\Exception $e) {
                if (is_object($webComic)) {
                    $output->writeln('[' . get_class($webComic) . '] Error fetching webcomic: ' . $e->getMessage());
                } else {
                    $output->writeln('[NO ' . $webComic . '] Error fetching webcomic: ' . $e->getMessage());
                }

            }
        }
    }

    /**
     * @return Service
     */
    public function getWebComicsService()
    {
        return $this->webComicsService;
    }

    /**
     * @param Service $webComicsService
     */
    public function setWebComicsService($webComicsService)
    {
        $this->webComicsService = $webComicsService;
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
     * @return Api
     */
    public function getTelegramService()
    {
        return $this->telegramService;
    }

    /**
     * @param Api $telegramService
     */
    public function setTelegramService($telegramService)
    {
        $this->telegramService = $telegramService;
    }
}
