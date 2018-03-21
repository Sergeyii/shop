<?php

namespace backend\bootstrap;

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\base\BootstrapInterface;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;
        $container->set(CKEditor::class, [
            'editorOptions' => ElFinder::ckeditorOptions('elfinder'),
        ]);

        $container->setSingleton(ManagerInterface::class, function() use($app) {
            return $app->authManager;
        });
    }
}