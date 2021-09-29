<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru-RU',
	'timeZone' => 'Asia/Almaty',
    'components' => [
        'i18n' => [
            'translations' => [
                'users*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=shanyrakplus',
            'username' => 'shanyrak',
            'password' => 'uX5k3r0?',
            'charset' => 'utf8',
        ],*/
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=briclub',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
		
    ],
];
