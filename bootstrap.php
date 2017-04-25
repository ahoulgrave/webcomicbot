<?php
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Knp\Provider\ConsoleServiceProvider;
use Telegram\Bot\Silex\Provider\TelegramControllerProvider;
use Telegram\Bot\Silex\Provider\TelegramServiceProvider;
use WebComicBot\Provider\ServiceProvider as WebComicBotServiceProvider;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();
//$app['debug'] = true;

$app->register(new \Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__.'/log/webcomic-' . date('Y-m-d') . '.log'
]);

$app->register(new GeckoPackages\Silex\Services\Config\ConfigServiceProvider(), [
    'config.dir' => __DIR__ . '/config',
    'config.format' => '%key%.yml'
]);

$dbCfg = $app['config']->get('db');

$app->register(new \Sorien\Provider\PimpleDumpProvider(), [
    'pimpledump.trigger_route_pattern' => '/_dump'
]);

$app->register(new Silex\Provider\DoctrineServiceProvider(), $dbCfg);

$app->register(new DoctrineOrmServiceProvider, [
    "orm.em.options" => [
        "mappings" => [
            [
                'type'      => 'annotation',
                'namespace' => 'WebComicBot\Entity',
                'path'      => realpath(__DIR__ . "/src"),
                'use_simple_annotation_reader' => false,
            ],
        ],
        ''
    ],
]);

$app->register(new ConsoleServiceProvider(), [
    'console.name'              => 'Web Comic Bot App',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__
]);

$app->register(new TelegramServiceProvider(), [
    'telegram.bot_api' => '274567037:AAHDA1T83BdPEFTgr8XqfhvSqOinnah7Cmw',
    'telegram.commands' => [
        \WebComicBot\Telegram\Command\StartCommand::class,
        \WebComicBot\Telegram\Command\ListCommand::class,
        \WebComicBot\Telegram\Command\SubscribeCommand::class,
        \WebComicBot\Telegram\Command\UnsubscribeCommand::class,
    ]
]);

$app->mount('/telegram', new TelegramControllerProvider());

$app->register(new WebComicBotServiceProvider());

$app['webcomicsbot']
    ->registerCommands([
        $app['webcomicsbot.command.fetch'],
        $app['webcomicsbot.command.setwebhook'],
        $app['webcomicsbot.command.removewebhook'],
    ])
    ->registerWebComics();

return $app;
