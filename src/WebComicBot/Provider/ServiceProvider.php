<?php
namespace WebComicBot\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WebComicBot\Command\FetchCommand;
use WebComicBot\Command\RemoveWebHookCommand;
use WebComicBot\Command\SetWebHookCommand;
use WebComicBot\Service\Service;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['webcomicsbot'] = function() use ($app) {
            return new Service($app['config'], $app['dispatcher'], $app['monolog'], $app['orm.em']);
        };
        $app['webcomicsbot.command.fetch'] = function () use ($app) {
            return new FetchCommand($app['webcomicsbot'], $app['orm.em'], $app['monolog'], $app['telegram']);
        };
        $app['webcomicsbot.command.setwebhook'] = function () use ($app) {
            return new SetWebHookCommand($app['telegram']);
        };
        $app['webcomicsbot.command.removewebhook'] = function () use ($app) {
            return new RemoveWebHookCommand($app['telegram']);
        };
    }
}
