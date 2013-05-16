<?php

namespace DoucheWeb;

use Douche\Interactor\AuctionListResponse;
use Douche\Interactor\AuctionViewRequest;
use Douche\Interactor\UserLoginRequest;
use Douche\Interactor\UserLoginResponse;
use Douche\Interactor\AuctionViewResponse;
use Douche\Exception\Exception as DoucheException;

use Mustache\Silex\Provider\MustacheServiceProvider;

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\ExceptionListenerWrapper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;

$app = new Application();

$app->register(new MonologServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new MustacheServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ServiceProvider());

$app->get('/', 'interactor.auction_list')
    ->value('controller', 'auction_list');

$app->get('/auction/{id}', 'interactor.auction_view')
    ->value('controller', 'auction_view')
    ->convert('request', function ($_, Request $request) {
        return new AuctionViewRequest($request->attributes->get('id'));
    });

$app->post('/login', 'interactor.user_login')
    ->value('controller', 'login')
    ->convert('request', function ($_, Request $request) {
        return new UserLoginRequest($request->request->all());
    });

// TODO change to ->on once fabpot/silex#705 is merged
$app['dispatcher'] = $app->share($app->extend('dispatcher', function ($dispatcher, $app) {
    $dispatcher->addListener(KernelEvents::EXCEPTION, new ExceptionListenerWrapper($app, function (DoucheException $e, $code) use ($app) {
        $controller = $app['request']->attributes->get('controller');

        if ($controller == 'login') {
            return [
                'errors' => ['Invalid Credentials'],
            ];
        }

    }), -8);

    return $dispatcher;
}));

$app->get('/login', function(Request $request, Application $app) {
    $view = [
        'errors' => [],
    ];

    return $app['mustache']->render('login.html.mustache', $view);
});

$app->get('/logout', function(Request $request, Application $app) {
    $request->getSession()->start();
    $request->getSession()->invalidate();
    return $app->redirect("/");
});

$app['resolver'] = $app->share($app->extend('resolver', function ($resolver, $app) {
    $resolver = new ControllerResolver($resolver, $app);

    return $resolver;
}));

// TODO change to ->on once fabpot/silex#705 is merged
$app['dispatcher'] = $app->share($app->extend('dispatcher', function ($dispatcher, $app) {
    $dispatcher->addListener(KernelEvents::VIEW, function ($event) use ($app) {
        $view = $event->getControllerResult();

        if (is_null($view) || is_string($view)) {
            return;
        }

        $request = $event->getRequest();

        if ($view instanceof UserLoginResponse) {
            $request->getSession()->set('current_user', $view->user);
            $event->setResponse(new RedirectResponse("/"));
            return;
        }

        $controller = $request->attributes->get('controller');
        $template = "$controller.html";

        $app['mustache']->addHelper('current_user', $request->getSession()->get('current_user'));

        $body = $app['mustache']->render($template, $view);
        $response = new Response($body);
        $event->setResponse($response);
    });

    return $dispatcher;
}));

return $app;
