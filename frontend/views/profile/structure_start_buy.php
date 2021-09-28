<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;

$this->title = "Матрица Start";
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',['depends'=>'yii\web\JqueryAsset']);
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->id);
}
 ?>
    <style>
        body{
            background-color: #1989F8!important;
        }
        .his.active {
            background: gray!important;
            color: #fff!important;
        }
    </style>
    <?
    $flashes = Yii::$app->session->allFlashes;
    if(!empty($flashes)){
        foreach ($flashes as $key => $flash) {?>
            <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert" style="padding-left: 10%;">
                <?=$flash?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?}}
    ?>
    <main class="structure-matrix">
        <div class="container">
            <div class="hgroup pb-2 text-center">
                <h1 class="h3"><?=$this->title?></h1>
            </div>
            <div class="hgroup pb-2 text-center">
                <a href="/profile/activ?program=1" class="btn block center-block btn-success">Выкупить место в матрице</a>
            </div>
        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>