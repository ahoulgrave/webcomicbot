<?php
namespace WebComicBot\Telegram\Command;

use Telegram\Bot\Actions;
use Telegram\Bot\Silex\Command\ApplicationAwareCommand;

class SubscribeCommand extends ApplicationAwareCommand
{
    /**
     * @inheritdoc
     */
    protected $name = 'subscribe';

    /**
     * @inheritdoc
     */
    public function handle($webComicName)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $update = $this->getUpdate();
        $app = $this->getApplication();
        $service = $app['webcomicsbot'];
        $user = $service->getOrCreateTelegramUser($update);
        $webComic = $service->findWebComicByName($webComicName);
        if (!$webComic) {
            $this->replyWithMessage([
                'text' => 'Sorry! I don\'t know that webcomic!'
            ]);
        } else if (in_array($webComic, $user->getWebComics())) {
            $this->replyWithMessage([
                'text' => sprintf('You are already subscribed to *%s*', $webComicName),
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $user->addWebComic($webComic);
            $em = $app['orm.em'];
            $em->persist($user);
            $em->flush();
            $this->replyWithMessage([
                'text' => sprintf(
                    'You to subscribed to *%s*. I will send you the comic when it\'s posted! 😉',
                    $webComicName
                ),
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}
