<?php
/**
 * Created by PhpStorm.
 * User: NextGen007
 * Date: 28.05.2017
 * Time: 18:56
 */
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'hostInfo' => $params['backendHostInfo'],
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:login|logout>' => 'site/<_a>',
        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];