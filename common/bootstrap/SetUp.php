<?php

namespace common\bootstrap;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use frontend\urls\CategoryUrlRule;
use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\HybridStorage;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\UserReadRepository;
use shop\services\ContactService;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\mail\MailerInterface;
use Yii;

class SetUp implements BootstrapInterface
{
    /* @param \yii\web\Application $app  */
    public function bootstrap($app){
        $container = Yii::$container;

        $container->setSingleton(MailerInterface::class, function() use($app){
            return $app->mailer;
        });

        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail']
        ]);

        $container->setSingleton(Client::class, function(){
           return ClientBuilder::create()->build();
        });

        /*//TODO::почему-то не работает!
        $container->setSingleton('cache', function () use($app){
            return $app->cache;
        });

        $container->set(CategoryUrlRule::class, [], [
            Instance::of(CategoryReadRepository::class),
            Instance::of('cache')
        ]);*/

        $container->setSingleton(Cart::class, function() use ($app){
            return new Cart(
                new HybridStorage($app->get('user'), 'cart', 604800, $app->db),
                new DynamicCost(new SimpleCost())
            );
        });

        $container->setSingleton(UserReadRepository::class, function(){
            return new UserReadRepository();
        });
    }
}