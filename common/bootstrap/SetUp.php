<?php

namespace common\bootstrap;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use frontend\urls\CategoryUrlRule;
use shop\cart\Cart;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\SessionStorage;
use shop\readModels\Shop\CategoryReadRepository;
use shop\services\ContactService;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\mail\MailerInterface;

/* @var $app \yii\web\Application */

class SetUp implements BootstrapInterface
{
    public function bootstrap($app){
        $container = \Yii::$container;

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

        $container->setSingleton(Cart::class, function(){
            return new Cart(new SessionStorage('cart'), new SimpleCost());
        });
    }
}