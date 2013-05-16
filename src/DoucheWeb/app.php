<?php

namespace DoucheWeb;

use Douche\Interactor\AuctionListResponse;
use Douche\Interactor\AuctionViewRequest;
use Douche\Interactor\AuctionViewResponse;

use Mustache\Silex\Provider\MustacheServiceProvider;

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;

$app = new Application();

$app->register(new MonologServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new MustacheServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new ServiceProvider());

$app->get('/', 'interactor.auction_list')
    ->value('controller', 'auction_list');

$app->get('/auction/{id}', 'interactor.auction_view')
    ->value('controller', 'auction_view')
    ->convert('request', function ($_, Request $request) {
        return new AuctionViewRequest($request->attributes->get('id'));
    });

$app['resolver'] = $app->share($app->extend('resolver', function ($resolver, $app) {
    $resolver = new ControllerResolver($resolver, $app);

    return $resolver;
}));

// TODO change to ->on once fabpot/silex#705 is merged
$app['dispatcher'] = $app->share($app->extend('dispatcher', function ($dispatcher, $app) {
    $dispatcher->addListener(KernelEvents::VIEW, function ($event) use ($app) {
        $view = $event->getControllerResult();

        $request = $event->getRequest();
        $controller = $request->attributes->get('controller');
        $template = "$controller.html";

        $body = $app['mustache']->render($template, $view);
        $response = new Response($body);
        $event->setResponse($response);
    });

    return $dispatcher;
}));

return $app;
