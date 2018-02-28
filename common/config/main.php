<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	 'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'debug',
    ],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['*'],
        ],
    ],
    'components' => [
       'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
        ],
        'robokassa' => [
            'class' => '\robokassa\Merchant',
            'baseUrl' => 'https://auth.robokassa.ru/Merchant/Index.aspx',
            'sMerchantLogin' => 'demo',
            'sMerchantPass1' => 'password_1',
            'sMerchantPass2' => '',
            'isTest' => true,
        ],
    ],
];
