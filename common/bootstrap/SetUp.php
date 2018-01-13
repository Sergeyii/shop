<?php

namespace common\bootstrap;

use shop\services\ContactService;
use shop\services\auth\PasswordResetService;
use yii\base\BootstrapInterface;
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
    }
}