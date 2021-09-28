<?php

/* @var $this yii\web\View */
/* @var $doc common\models\Documents */
use yii\httpclient\Client;
use yii\web\View;


$this->title = $doc['title'];
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$img = $doc['link'];
?>
    <main class="cours">
        <div class="container">
            <div class="row">
                <main role="main" class="col-12">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center hgroup">
                        <h1 class="h1"><?=$this->title?></h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <img  src="<?=$img?>" style="border-radius: 10px;" class="card-img-top" alt="...">
                            <p><?=$doc['description']?></p>
                        </div>
                    </div>

                </main>

            </div>
        </div>



    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>