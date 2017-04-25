<?php
namespace WebComicBot\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Telegram\Bot\Api;

class GetWebHookInfoCommand extends Command
{
    /**
     * @var Api
     */
    private $telegramService;

    /**
     * SetWebHookCommand constructor.
     * @param Api $telegramService
     */
    public function __construct(Api $telegramService)
    {
        parent::__construct();
        $this->setTelegramService($telegramService);
    }

    protected function configure()
    {
        $this->setName('wcb:getwebhookinfo')
            ->setDescription('Sets the bot webhook url');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tg = $this->getTelegramService();
        $response = $tg->removeWebhook();
        var_dump($response);
        //$output->writeln('Result: ' . $response->getHttpStatusCode());
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
