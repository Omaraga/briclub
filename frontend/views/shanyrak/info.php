<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Shanyrak";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$this->registerJs('
   
');
//$this->registerJsFile('/js/jquery.maskedinput.min.js',['depends'=>'yii\web\JqueryAsset']);

 ?>
    <main class="transaction">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Shanyrak информация</h1>
                <div class="hline"></div>
            </div>

            <div class="row">
                <? foreach ($programs as $program) {
                    ?>
                    <div class="col-lg-10 mt-4" >
                        <p><?=$program['title']?></p>
                        <p><?=$program['description']?></p>
                        <p><?=$program['text']?></p>
                    </div>
                <?}?>
            </div>

        </div>
    </main>


<?
echo \frontend\components\LoginWidget::widget();
?>