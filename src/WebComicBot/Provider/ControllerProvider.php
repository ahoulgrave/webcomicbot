<?php
namespace WebComicBot\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed|\Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/webhook', function () {
            return new Response('Webhook for telegram bot');
        });
        $controllers->post('/webhook', function(Request $request) use ($app) {
            $app['telegram']->commandsHandler(true);
            return new Response();
        });
        return $controllers;
    }
}
