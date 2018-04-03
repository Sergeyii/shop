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
    'aliases' => [
        '@staticRoot' => $static['staticPath'],
        '@static' => $static['staticHostInfo'],
    ],
    'mailChimp' => [
        'apiKey' => '',
        'listId' => '',
    ],
];
