<?php
$static = [
    'staticHostInfo' => 'http://static.shop.local',
    'staticPath' => dirname(__DIR__, 2).'/static',
];

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 3600,
    'cookieValidationKey' => '',
    'cookieDomain' => '.example.com',
    'frontendHostInfo' => 'http://example.com',
    'backendHostInfo' => 'http://backend.example.com',
    'aliases' => [
        '@staticRoot' => $static['staticPath'],
        '@static' => $static['staticHostInfo'],
    ],
    'mailChimp' => [
        'apiKey' => '',
        'listId' => '',
    ],
    'sms' => [
        'api_id' => '',
        'base_url' => '',
    ],
    'flysystem' => [
        'ftp' => [
            'host' => 'ftp.example.com',
            'username' => 'username',
            'password' => 'password',

            /** optional config settings */
            'port' => 21,
            'root' => '/path/to/root',
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
        ]
    ],
];
