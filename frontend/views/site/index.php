<?php

/* @var $this yii\web\View */

$this->title = 'Sahnyrak';
$get = Yii::$app->request->get();
$ref_cookie = null;
if(!empty($get['referal'])){
    $cookies = Yii::$app->response->cookies;
    $cookies->add(new \yii\web\Cookie([
        'name' => 'referal',
        'value' => $get['referal'],
    ]));
}
use yii\helpers\Html; ?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8" />


</head>

