<?php
namespace WebComicBot\Telegram\Command;

use Telegram\Bot\Actions;
use Telegram\Bot\Silex\Command\ApplicationAwareCommand;

class ListCommand extends ApplicationAwareCommand
{
    /**
     * @inheritdoc
     */
    protected $name = 'list';

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $app = $this->getApplication();
        $update = $this->getUpdate();
        $service = $app['webcomicsbot'];
        $webComics = $service->getWebComics();
        sort($webComics, SORT_FLAG_CASE);
        $user = $service->getOrCreateTelegramUser($update);
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        if ($arguments == 'subscribed') {
            if (!empty($user->getWebComics())) {
                $message = 'Here are the web comics you are currently subscribed to:' . PHP_EOL . PHP_EOL;
                foreach ($user->getWebComics() as $webComic) {
                    $message.= '✔️' . $webComic::getName() . PHP_EOL;
                }
                $this->replyWithMessage([
                    'text' => $message,
                    'parse_mode' => 'Markdown'
                ]);
            } else {
                $this->replyWithMessage([
                    'text' => 'You are not subscribed to any web comic! Try `/list`.',
                    'parse_mode' => 'Markdown'
                ]);
            }
        } else {
            $message = 'Alright, here are the comics you can subscribe to.' . PHP_EOL;
            $message.= 'Just type `/subscribe <name of the webcomic>` to receive the webcomic whenever there\'s a new one.'. PHP_EOL . PHP_EOL;
            foreach ($webComics as $webComic) {
                $isSubscribed = in_array(get_class($webComic), $user->getWebComics());
                $message.= ($isSubscribed ? '✔️': '✖️') . ' ' . $webComic::getName() . PHP_EOL;
            }
            $this->replyWithMessage([
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}
