<?php
/* @var $this yii\web\View */
/* @var $bill \common\models\Bills */
/* @var $receiver string*/
/* @var $tokens double*/
/* @var $bonusTokens double*/

$this->title = 'Оплата счета №' . $bill->code;
$user = \common\models\User::findOne(Yii::$app->user->identity['id']);

$form = \yii\widgets\ActiveForm::begin();?>
<main class="opl container">
    <div class="row">
        <div class="col-12 col-md-7">
            <div class="baner">
                <?if($bill->status == 2){?>
                    <h5><?=$this->title?></h5>
                    <p class="text"><?=$bill->comment?></p>
                    <!--                    <p>Получатель: --><?//=$receiver?><!--</p>-->
                    <p>Ваш баланс: <?=$user['w_balans'];?>CV</p>
                    <div class="line">
                        <span>Счет на оплату: <?=$bill->sum?> CV</span>
                    </div>

                    <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="Оплатить">
                <?}else if($bill->status == 1){?>
                    <h2>Счет был оплачен</h2>
                    <p>Дата и время оплаты: <?=date("d.m.Y H:i",$bill->updated_at)?></p>
                <?} ?>
            </div>
        </div>
    </div>
</main>
<?php
$this->registerJs('$(window).bind("beforeunload", function(){
    $(".btn").attr("disabled", true);
});
');
 \yii\widgets\ActiveForm::end()?>
