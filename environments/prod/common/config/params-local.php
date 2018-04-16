<?php
return [
    'cookieValidationKey' => '',
    'cookieDomain' => '.shop.local',
    'frontendHostInfo' => 'http://shop.local',
    'backendHostInfo' => 'http://backend.shop.local',
    'mailChimp' => [
        'apiKey' => '4198e479b00741064c31293160b847e6-us12',
        'listId' => 'cd03314562',
    ],
    'sms' => [
        'api_id' => '',
        'base_url' => 'https://sms.ru/sms/send',
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
