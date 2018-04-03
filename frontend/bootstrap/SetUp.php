<?php

namespace frontend\bootstrap;

use shop\services\newsletter\MailChimp;
use shop\services\newsletter\Newsletter;
use shop\services\yandex\ShopInfo;
use shop\services\yandex\YandexMarket;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;

/* @var $app \yii\web\Application */
class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->set(Breadcrumbs::class, function ($container, $params, $args) {
            return new Breadcrumbs(ArrayHelper::merge([
                'homeLink' => [
                    'label' => '<i class="fa fa-home"></i>',
                    'encode' => false,
                    'url' => \Yii::$app->homeUrl,
                ],
            ], $args));
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
    }
}