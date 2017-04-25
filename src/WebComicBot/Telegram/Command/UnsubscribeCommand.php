<?php
namespace WebComicBot\Telegram\Command;

use Telegram\Bot\Actions;
use Telegram\Bot\Silex\Command\ApplicationAwareCommand;

class UnsubscribeCommand extends ApplicationAwareCommand
{
    /**
     * @inheritdoc
     */
    protected $name = 'unsubscribe';

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
        if ($user->removeWebComic($webComic)) {
            $em = $app['orm.em'];
            $em->persist($user);
            $em->flush();
            $this->replyWithMessage([
                'text' => sprintf('You will no longer receive *%s* comics 😔', $webComicName),
                'parse_mode' => 'Markdown'
            ]);
        } else {
            $this->replyWithMessage([
                'text' => sprintf('I\'m sorry, but you are not subscribed to *%s*... 🤔' . PHP_EOL . 'Type `/list subscribed` to see you subscription list', $webComicName),
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}
