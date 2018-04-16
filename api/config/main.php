<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'aliases' => $params['aliases'],
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => yii\web\Response::FORMAT_JSON,
                'application/xml' => yii\web\Response::FORMAT_XML,
            ],
        ],
    ],
    'modules' => [
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24,
            'storageMap' => [
                'user_credentials' => 'common\auth\Identity',
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ]
            ]
        ]
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'formatters' => [
                'json' =>[
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\auth\Identity',
            'enableAutoLogin' => false,//Запрет автоматического логина
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,//Включаем ЧПУ
            'enableStrictParsing' => true,//Закрываем возможность для перехода напрямую к действию контроллеров.
            'showScriptName' => false,//Не выводить *.php
            'rules' => [
                '' => 'site/index',
                'profile' => 'user/profile/index',
                'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',

                'GET shop/products/<id:\d+>' => 'shop/product/view',
                'GET shop/products/category/<id:\d+>' => 'shop/product/category',
                'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
                'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
                'GET shop/products' => 'shop/product/index',
                'POST shop/products/<id:\d+>/wish' => 'shop/wishlist/add',
                'POST shop/products/<id:\d+>/cart' => 'shop/cart/add',

                'GET shop/wishlist' => 'shop/wishlist/index',
                'DELETE shop/wishlist/<id:\d+>' => 'shop/wishlist/delete',

                'GET shop/cart' => 'shop/cart/index',
                'DELETE shop/cart' => 'shop/cart/clear',
                'POST shop/cart/checkout' => 'shop/checkout/index',
                'POST shop/cart/<id:\d+>/quantity' => 'shop/cart/quantity',
                'DELETE shop/cart/<id:\d+>' => 'shop/cart/delete',
            ],
        ],
    ],
    'as authenticator' => [
        'class' => 'filsh\yii2\oauth2server\filters\auth\CompositeAuth',
        'except' => ['site/index', 'oauth2/rest/token',],
        'authMethods' => [
            ['class' => 'yii\filters\auth\HttpBearerAuth'],
            ['class' => 'yii\filters\auth\QueryParamAuth', 'tokenParam' => 'accessToken'],
        ]
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/index', 'oauth2/rest/token'],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'as exceptionFilter' => [
        'class' => 'filsh\yii2\oauth2server\filters\ErrorToExceptionFilter'
    ],
    'params' => $params,
];