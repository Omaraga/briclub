<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $coursePrice float */
/* @var $referralForm frontend\modules\academy\models\forms\ReferralForm */
/* @var $course common\models\Courses */

use yii\widgets\ActiveForm;

$script = <<<JS
    $('#checkOffer').change(function (e){
        if ($(this).is(':checked')){
            $('#buttonBuy').attr('disabled', false)
        }else{
            $('#buttonBuy').attr('disabled', true)
        }
    });
JS;
$this->registerJs($script);
?>

<section class="mt-5">
    <div class="container">
        <?$form = ActiveForm::begin(['enableClientValidation'=>false, 'enableAjaxValidation'=>false,])?>
            <div>
                <h3>Оплата покупки</h3>
                <?if($course->type == 2 && $user->activ != 1):?>
                    <div class="mt-3">
                        <h5 class="w5">Укажите логин рекомендателя</h5>
                            <?=$form->field($referralForm, 'referralName')->textInput(['class' => 'my-3 input-defult', 'placeholder' => 'Логин', 'readonly'=>($user->parent_id)?true:false])->label(false)?>
                            <div>
                                <?if(!$user->parent_id):?>
                                    <?=$form->field($referralForm, 'isReferral')->checkbox($options = ['uncheck' => 0, 'value' => 1, 'label' => 'Продолжить без рекомендателя'], $enclosedByLabel = true);?>
                                <?endif;?>
                            </div>
                    </div>
                <?endif;?>
            </div>
            <div class="row">
                <div class="opl-balance col-12 col-md-6">
                    <div class="opl-balance_card fon-grey">
                        <h3 class="mb-2">Ваш баланс: CV <?=$user->w_balans?></h3>
                            <a class="text-green" href=""><h6>Пополнить баланс</h6></a>
                            <?if($coursePrice == 0):?>
                                <h3>Бесплатно</h3>
                            <?else:?>
                                <h3>Стоимость: CV <?=$coursePrice;?></h3>
                            <?endif;?>


                            <div class="balance_card-panel">
                                <?if($coursePrice > $user->w_balans):?>
                                    <a href="/academy/pay">
                                        <button class="btn fon-green" type="button">Пополнить</button>
                                    </a>
                                <?elseif($coursePrice > 0):?>
                                    <button class="btn fon-green" type="submit" id="buttonBuy" disabled>Купить</button>
                                <?else:?>
                                    <button class="btn fon-green" type="submit" id="buttonBuy" disabled>Получить</button>
                                <?endif;?>
                            </div>
                            <div class="d-flex mt-3">
                                <input type="checkbox" id="checkOffer">
                                <h6 class="ml-2">Я согласен с <a href="/docs/system/оферта.pdf" class="text-green">Договором оферты</a></h6>
                            </div>

                    </div>
                </div>
                <div class="col-11 col-sm-7 col-md-4 ml-3 fon-border-line opl-balance_card">
                    <div>

                        <div class="card_img <?=($course->icon_color)?$course->icon_color:'violet'?> center">
                            <img src="<?=$course->icon_url;?>" style="max-width: 100%" alt="">
                        </div>
                    </div>
                    <div class="card_text-group">
                        <h5 class="w8 card_text"><?=$course->title?></h5>
                        <p>LSE platform</p>
                        <div class="mt-4">
                            <span><?=$course->duration;?></span>
                            <span>Онлайн</span>
                        </div>
                    </div>
                    <h4 class="card_sum w8">От CV <?=$course->price;?></h4>
                </div>
            </div>
        <?ActiveForm::end()?>
    </div>
</section>
