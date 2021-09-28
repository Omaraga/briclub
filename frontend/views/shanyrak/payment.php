<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Оплата ежемесячного платежа";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}

 ?>
<?
$flashes = Yii::$app->session->allFlashes;
if(!empty($flashes)){
    foreach ($flashes as $key => $flash) {?>
        <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
            <?=$flash?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?}}
?>
    <main class="transaction">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Оплата ежемесячного платежа</h1>
                <div class="hline"></div>
            </div>

            <div class="row">
                <div class="col-lg-10">
                    <p>Ваш баланс: <?=$user['w_balans']?></p>

                    <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-shan']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?= $form->field($model, 'sum')->textInput(['placeholder'=>'Введите сумму перевода','value'=>$pay['sum_need']])->label(false); ?>
                                </div>
                                <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="<?=Yii::t('users', 'Оплатить')?>">
                            </div>
                        </div>
                    <?php \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>