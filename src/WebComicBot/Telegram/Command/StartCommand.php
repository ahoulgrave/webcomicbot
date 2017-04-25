<?php
namespace WebComicBot\Telegram\Command;

use Telegram\Bot\Silex\Command\ApplicationAwareCommand;

class StartCommand extends ApplicationAwareCommand
{
    /**
     * @inheritdoc
     */
    protected $name = 'start';

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $update = $this->getUpdate();
        $app = $this->getApplication();
        $app['monolog']->info($update->getMessage()->getText());

        $service = $app['webcomicsbot'];

        $user = $service->getOrCreateTelegramUser($update);

        if ($user->isJustCreated()) {
            $this->replyWithMessage([
                'text' => 'Hi! I am the Web Comics Bot! I was programmed to deliver internet web comics. Type `/list` to see the available web comics',
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $app['telegram']->sendMessage([
                'chat_id' => $user->getChatId(),
                'text' => 'Hi there!'
            ]);
        }
    }
}
