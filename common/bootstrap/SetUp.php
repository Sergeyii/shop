<?php

namespace common\bootstrap;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use frontend\urls\CategoryUrlRule;
use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\HybridStorage;
use shop\dispatchers\EventDispatcher;
use shop\dispatchers\SimpleEventDispatcher;
use shop\listeners\User\UserSignupConfirmedListener;
use shop\listeners\User\UserSignupRequestedListener;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\UserReadRepository;
use shop\services\newsletter\MailChimp;
use shop\services\newsletter\Newsletter;
use shop\services\sms\LoggedSender;
use shop\services\sms\SmsRu;
use shop\services\sms\SmsSender;
use shop\services\yandex\ShopInfo;
use shop\services\yandex\YandexMarket;
use shop\useCases\auth\events\UserSignUpConfirmed;
use shop\useCases\auth\events\UserSignUpRequested;
use shop\useCases\ContactService;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\mail\MailerInterface;
use Yii;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    /* @param \yii\web\Application $app  */
    public function bootstrap($app){
        $container = Yii::$container;

        $container->setSingleton(MailerInterface::class, function() use($app){
            return $app->mailer;
        });

        $container->setSingleton(ManagerInterface::class, function() use($app) {
            return $app->authManager;
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

        $container->setSingleton(YandexMarket::class, [], [
            new ShopInfo($app->name, $app->name, $app->params['frontendHostInfo']),
        ]);

        $container->setSingleton(Newsletter::class, function() use($app){
            return new MailChimp(
                new \DrewM\MailChimp\MailChimp($app->params['mailChimp']['apiKey']),
                $app->params['mailChimp']['listId']
            );
        });

        $container->setSingleton(UserReadRepository::class, function(){
            return new UserReadRepository();
        });

        $container->setSingleton(SmsSender::class, function() use($app){
            return new LoggedSender(
                new SmsRu($app->params['sms']['api_id'], $app->params['sms']['base_url']),
                Yii::getLogger()
            );
        });

        $container->setSingleton(EventDispatcher::class, function() use ($container){
            return new SimpleEventDispatcher([
                UserSignUpRequested::class => [
                    [$container->get(UserSignupRequestedListener::class), 'handle'],
                ],
                UserSignUpConfirmed::class => [
                    [$container->get(UserSignupConfirmedListener::class), 'handle'],
                ],
            ]);
        });
    }
}