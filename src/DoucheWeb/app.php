<?php

namespace DoucheWeb;

use Douche\Interactor\AuctionListResponse;
use Douche\Interactor\AuctionViewRequest;
use Douche\Interactor\UserLoginRequest;
use Douche\Interactor\UserLoginResponse;
use Douche\Interactor\AuctionViewResponse;
use Douche\Interactor\BidRequest;
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

use Money\Money;
use Money\Currency;

$app = new Application();

$app->register(new MonologServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new MustacheServiceProvider(), [
    'mustache.options' => [
        'helpers' => [
            'format_money' => function ($money) {
                return $money->getCurrency().' '.($money->getAmount() / 100);
            },
            'format_date' => function (\DateTime $date) {
                return $date->format("Y-m-d H:i:s");
            },
        ],
    ],
]);
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

$app->post('/auction/{id}/bids', 'interactor.bid')
    ->before(function (Request $request, Application $app) {
        if (!$request->getSession()->has('current_user')) {
            return $app->abort(401, 'Authenitcation Required');
        }
    })
    ->value('controller', 'bid')
    ->value('success_handler', function ($view, $request) {
        return new RedirectResponse("/auction/" . $request->attributes->get('id'));
    })
    ->value('error_handlers', [
        "Douche\Exception\BidTooLowException" => function ($e, $code, $request) {
            $request->getSession()->getFlashBag()->set('errors', [
                'The provided bid was too low.',
            ]);
            return new RedirectResponse("/auction/" . $request->attributes->get('id'));
        },
    ])
    ->convert('request', function ($_, Request $request) {
        return new BidRequest(
            $request->attributes->get('id'),
            $request->getSession()->get('current_user')->id,
            new Money((int) $request->request->get('amount') * 100, new Currency($request->request->get('currency')))
        );
    });

$app->post('/login', 'interactor.user_login')
    ->value('controller', 'login')
    ->value('success_handler', function ($view, $request) {
        $request->getSession()->set('current_user', $view->user);
        return new RedirectResponse("/");
    })
    ->value('error_handlers', [
        "Douche\Exception\UserNotFoundException" => function ($e) {
            return [
                'errors' => ['Incorrect email provided.'],
                'email' => $e->email,
            ];
        },
        "Douche\Exception\IncorrectPasswordException" => function ($e) {
            return [
                'errors' => ['Invalid credentials provided.'],
                'email' => $e->email,
            ];
        },
    ])
    ->convert('request', function ($_, Request $request) {
        return new UserLoginRequest($request->request->all());
    });

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

$app->error(function (DoucheException $e, $code) use ($app) {
    $app['request']->attributes->set('failed', true);

    $errorHandlers = $app['request']->attributes->get('error_handlers', []);

    foreach ($errorHandlers as $type => $handler) {
        if ($e instanceof $type) {
            return $handler($e, $code, $app['request']);
        }
    }
});

$app->on(KernelEvents::VIEW, function ($event) use ($app) {
    $view = $event->getControllerResult();

    if (is_null($view) || is_string($view)) {
        return;
    }

    $request = $event->getRequest();

    if (!$request->attributes->get('failed') && $request->attributes->has('success_handler')) {
        $handler = $request->attributes->get('success_handler');

        $view = $handler($view, $request);
        if ($view instanceof Response) {
            $event->setResponse($view);
            return;
        }
    }

    $controller = $request->attributes->get('controller');
    $template = "$controller.html";

    $view = (object) $view;
    $view->current_user = $request->getSession()->get('current_user');
    $view->form_errors = $request->getSession()->getFlashBag()->get('errors');

    $body = $app['mustache']->render($template, $view);
    $response = new Response($body);
    $event->setResponse($response);
});

$app->after(function () use ($app) {
    $app['douche.auction_repo']->save();
});

return $app;
