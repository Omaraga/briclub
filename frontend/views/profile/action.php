<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = $news['title'];
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$withdraws = \common\models\Withdraws::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->all();
$actions = \common\models\Actions::find()->where(['user_id'=>$user['id']])->orWhere(['type'=>104,'status'=>1])->orderBy('id desc')->all();
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
                            <p class="small"><?=date('d.m.Y H:i',$news['time'])?></p>
                            <?=$news['content']?>
                        </div>
                    </div>

                </main>

            </div>
        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>