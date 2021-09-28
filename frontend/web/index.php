<?php
/*echo "На сайте ведутся технические работы до 01:00 четверг, 22 октября 2020 г. По времени (GMT+3) Москва, Россия";*/
$work = true;

define("_DATE_", "16:00 01.02.2021");

if(!$work){
    require "site_unavailable.php";
}
else{
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'prod');

    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
    require __DIR__ . '/../../common/config/bootstrap.php';
    require __DIR__ . '/../config/bootstrap.php';

    $config = yii\helpers\ArrayHelper::merge(
        require __DIR__ . '/../../common/config/main.php',
        require __DIR__ . '/../../common/config/main-local.php',
        require __DIR__ . '/../config/main.php',
        require __DIR__ . '/../config/main-local.php'
    );

    (new yii\web\Application($config))->run();
}