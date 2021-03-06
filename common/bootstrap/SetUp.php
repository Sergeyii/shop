<?php

namespace common\bootstrap;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Filesystem;
use shop\dispatchers\AsyncEventDispatcher;
use shop\entities\behaviors\FlySystemImageUploadBehavior;
use shop\entities\Shop\Product\events\ProductAppearedInStock;
use frontend\urls\CategoryUrlRule;
use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\HybridStorage;
use shop\dispatchers\DeferredEventDispatcher;
use shop\dispatchers\EventDispatcher;
use shop\dispatchers\SimpleEventDispatcher;
use shop\jobs\AsyncEventJobHandler;
use shop\listeners\Shop\Category\CategoryPersistenceListener;
use shop\listeners\Shop\Product\ProductAppearedInStockListener;
use shop\listeners\Shop\Product\ProductSearchPersistListener;
use shop\listeners\Shop\Product\ProductSearchRemoveListener;
use shop\listeners\User\UserSignupConfirmedListener;
use shop\listeners\User\UserSignupRequestedListener;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\UserReadRepository;
use shop\repositories\events\EntityPersisted;
use shop\repositories\events\EntityRemoved;
use shop\services\newsletter\MailChimp;
use shop\services\newsletter\Newsletter;
use shop\services\sms\LoggedSender;
use shop\services\sms\SmsRu;
use shop\services\sms\SmsSender;
use shop\services\yandex\ShopInfo;
use shop\services\yandex\YandexMarket;
use shop\entities\User\events\UserSignUpConfirmed;
use shop\entities\User\events\UserSignUpRequested;
use shop\useCases\ContactService;
use yii\base\BootstrapInterface;
use yii\base\ErrorHandler;
use yii\di\Container;
use yii\di\Instance;
use yii\mail\MailerInterface;
use Yii;
use yii\queue\Queue;
use yii\rbac\ManagerInterface;
use yiidreamteam\upload\ImageUploadBehavior;

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

        $container->setSingleton(ErrorHandler::class, function() use($app) {
            return $app->errorHandler;
        });

        $container->setSingleton(Queue::class, function() use ($app) {
            return $app->get('queue');
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

        $container->setSingleton(EventDispatcher::class, DeferredEventDispatcher::class);

        $container->setSingleton(DeferredEventDispatcher::class, function (Container $container) {
            return new DeferredEventDispatcher(new AsyncEventDispatcher($container->get(Queue::class)));
        });

        $container->setSingleton(SimpleEventDispatcher::class, function(Container $container){
            return new SimpleEventDispatcher($container, [
                UserSignUpRequested::class => [UserSignupRequestedListener::class],
                UserSignUpConfirmed::class => [UserSignupConfirmedListener::class],
                ProductAppearedInStock::class => [ProductAppearedInStockListener::class],
                EntityPersisted::class => [
                    ProductSearchPersistListener::class,
                    CategoryPersistenceListener::class,
                ],
                EntityRemoved::class => [
                    ProductSearchRemoveListener::class,
                    CategoryPersistenceListener::class,
                ],
            ]);
        });

        $container->setSingleton(AsyncEventJobHandler::class, [], [
            Instance::of(SimpleEventDispatcher::class)
        ]);

        /*$container->setSingleton(Filesystem::class, function() use($app) {
            return new Filesystem(new Ftp($app->params['flysystem']['ftp']));
        });

        $container->set(ImageUploadBehavior::class, FlySystemImageUploadBehavior::class);*/
    }
}