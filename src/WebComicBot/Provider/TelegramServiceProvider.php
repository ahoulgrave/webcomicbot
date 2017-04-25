<?php
namespace WebComicBot\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Telegram\Bot;

class TelegramServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @throws \Exception
     */
    public function register(Container $app)
    {
        $app['telegram'] = function () use ($app) {
            if (!isset($app['telegram.bot_api'])) {
                throw new \Exception('telegram.bot_api parameter must be set.');
            }
            $botApi = new Bot\Api($app['telegram.bot_api']);
            if (
                isset($app['telegram.commands'])
                && is_array($app['telegram.commands'])
            ) {
                foreach ($app['telegram.commands'] as $command) {
                    $botApi->addCommand(new $command($app));
                }
            }
            return $botApi;
        };
    }
}
