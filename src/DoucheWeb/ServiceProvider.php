<?php

namespace DoucheWeb;

use Douche\Storage\Sql\AuctionRepository;
use Douche\Storage\File\UserRepository;
use Douche\Interactor\AuctionList;
use Douche\Interactor\AuctionView;
use Douche\Interactor\UserLogin;
use Douche\Service\UppercasePasswordEncoder;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * External dependencies
 *
 * Services:
 * - db (DoctrineServiceProvider)
 *
 * Parameters:
 * - douche.user_repo.file: string filename for json storage
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['douche.user_repo'] = $app->share(function ($app) {
            return new UserRepository($app['douche.user_repo.file']);
        });

        $app['douche.auction_repo'] = $app->share(function ($app) {
            return new AuctionRepository($app['db'], $app['douche.user_repo']);
        });

        $app['douche.password_encoder'] = $app->share(function ($app) {
            return new UppercasePasswordEncoder;
        });

        $app['interactor.auction_list'] = $app->share(function ($app) {
            return new AuctionList($app['douche.auction_repo']);
        });

        $app['interactor.auction_view'] = $app->share(function ($app) {
            return new AuctionView($app['douche.auction_repo']);
        });

        $app['interactor.user_login'] = $app->share(function ($app) {
            return new UserLogin($app['douche.user_repo'], $app['douche.password_encoder']);
        });
    }

    public function boot(Application $app)
    {
    }
}
