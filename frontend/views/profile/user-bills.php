<?php

/* @var $this yii\web\View */
/* @var $bills \common\models\Bills[] */
use yii\httpclient\Client;


$this->title = "Ваши счета";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$withdraws = \common\models\Withdraws::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->all();
$actions = \common\models\Actions::find()->where(['user_id'=>$user['id']])->orWhere(['type'=>104,'status'=>1])->orderBy('id desc')->all();
?>
    <main class="activition">
        <div class="container">
            <div style="margin-top: 10px;">
                <?if($bills != null){?>
                        <p>
                            <b>Ваши счета:</b>
                        </p>
                    <ul>
                        <?foreach ($bills as $bill){?>
                            <li><a href="pay-bill?code=<?=$bill->code?>">Счет №<?=$bill->code?></a></li>
                        <?}?>
                    </ul>
                <?}else{?>
                    <h2 style="color: grey; margin-top: 10px;">У Вас нет счетов</h2>
                <?}?>
            </div>
        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>