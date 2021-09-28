<?php

/* @var $this yii\web\View */
/* @var $promo string */
use yii\httpclient\Client;
use yii\web\View;
//echo "На этой странице технические работы до 02:20 по времени Нур-Султана";
//exit;

$get = Yii::$app->request->get();
$ref_cookie = null;


$this->title = "Токены";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$course = \common\models\Changes::findOne(3)['cur2'];

$bonus = 10;
$min1 = \common\models\TokenBonuses::findOne(2)['count_from'];
$max1 = \common\models\TokenBonuses::findOne(2)['count_to'];
$bonus1 = \common\models\TokenBonuses::findOne(2)['percent'];

$min2 = \common\models\TokenBonuses::findOne(3)['count_from'];
$max2 = \common\models\TokenBonuses::findOne(3)['count_to'];
$bonus2 = \common\models\TokenBonuses::findOne(3)['percent'];

$min3 = \common\models\TokenBonuses::findOne(4)['count_from'];
$max3 = \common\models\TokenBonuses::findOne(4)['count_to'];
$bonus3 = \common\models\TokenBonuses::findOne(4)['percent'];


$this->registerJs('
             
        $( "#gettokenform-tokens" ).keyup(function() {
              tokens = parseInt($(this).val());
              if(tokens>'.$min1.' && tokens <= '.$max1.'){
                bonus = '.$bonus1.';
              }
              else if(tokens>'.$min2.' && tokens <= '.$max2.'){
                bonus = '.$bonus2.';
              }
              else if(tokens>'.$min3.' && tokens <= '.$max3.'){
                bonus = '.$bonus3.';
              }
              $( "#gettokenform-sum" ).val(tokens*'.$course.');
              token_bonus = tokens*(bonus/100)
              $( "#token_bonus" ).html(token_bonus);
              $( "#bonus-percent" ).html(bonus);
             $("#token_final").html(token_bonus + tokens)
        });
        
        $( "#gettokenform-sum" ).keyup(function() {
              sum = parseInt($(this).val());
              $( "#gettokenform-tokens" ).val(sum/'.$course.');
              tokens = parseInt($("#gettokenform-tokens").val());
              if(tokens>'.$min1.' && tokens <= '.$max1.'){
                bonus = '.$bonus1.';
              }
              else if(tokens>'.$min2.' && tokens <= '.$max2.'){
                bonus = '.$bonus2.';
              }
              else if(tokens>'.$min3.' && tokens <= '.$max3.'){
                bonus = '.$bonus3.';
              }
               
               token_bonus = sum/'.$course.'*(bonus/100);
                $( "#token_bonus" ).html(token_bonus);
                $( "#bonus-percent" ).html(bonus);
                $("#token_final").html(token_bonus + tokens)
        });
        
        $(window).bind("beforeunload", function(){
             $(".btn").attr("disabled", true);
        });
    ');
?>
    <main class="transaction">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Купить токены</h1>
                <small style="color: #ff7601;">
                    Уважаемый пользователь! Вывод средств осуществляется только на верифицированные платежные системы.
                    <br>
                    Компания предостерегает, в случае перевода средств с аккаунта на аккаунт для вывода,<br>
                    каждый пользователь несет личную ответственность.
                </small>
                <div class="hline"></div>
            </div>

            <div class="row">
                <div class="col-lg-10">
                    <p>Стоимость 1 токена: <?=\common\models\Changes::findOne(3)['cur2']?> US</p>
<!--                    <p>Бонусы в токенах: <span id="bonus-percent">0</span>% до 31.01.2021</p>-->
                    <?
                    if(!empty($error)){?>
                        <p class="alert-danger"><?=$error?></p>
                    <?}?>
                    <?
                    if(!empty($success)){?>
                        <p class="alert-success"><?=$success?></p>
                    <?}?>
                    <?if(empty($price)){?>
                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'form-get-tokens']); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?
                                        echo $form->field($model, 'parent',['options'=>['class'=>'form-group']])->textInput(['placeholder'=>'Промокод','value'=>$promo,['options'=>['class'=>'form-control']]]);
                                    ?>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($model, 'sum')->textInput(['placeholder'=>'Введите сумму']); ?>
                                </div>
                                <div class="form-group mt-3">
                                    <?= $form->field($model, 'tokens')->textInput(['placeholder'=>'Количество токенов']); ?>
                                </div>
                                <!--                            <p>Бонусы: <span id="token_bonus"></span></p>-->
                                <!--                            <p>Итого: <span id="token_final"></span></p>-->
                                <?= $form->field($model, 'system_id')->hiddenInput(['value'=>2])->label(false); ?>
                                <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="<?=Yii::t('users', 'Купить')?>">
                            </div>
                        </div>
                        <?php \yii\widgets\ActiveForm::end(); ?>
                    <?}else{?>
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="perfect-tab" data-toggle="tab" href="#perfect" role="tab" aria-controls="home" aria-selected="true">PerfectMoney</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Visa/Mastercard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#payeer" role="tab" aria-controls="payeer" aria-selected="false">Payeer</a>
                                    </li>

                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade in active show" id="perfect" role="tabpanel" aria-labelledby="perfect-tab">
                                        <h2 class="h2 mb-0">PerfectMoney</h2>
                                        <form class="form" action="https://perfectmoney.com/api/step1.asp" method="POST">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <p>К оплате: <? echo $price;?></p>
                                                    <input type="hidden" name="PAYEE_ACCOUNT" value="U13740002">
                                                    <input type="hidden" name="PAYEE_NAME" value="Shanyrakplus.com">
                                                    <input type="hidden" name="PAYMENT_ID" value="<?=$user['id']?>-<?=$program?>-<?=$promo?>">
                                                    <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                                    <input type="hidden" name="STATUS_URL" value="https://shanyrakplus.com/payment/status">
                                                    <input type="hidden" name="PAYMENT_URL" value="https://shanyrakplus.com/payment/activ">
                                                    <input type="hidden" name="NOPAYMENT_URL" value="https://shanyrakplus.com/payment/fail">

                                                    <div class="form-group mt-3">
                                                        <input type="hidden" placeholder="Введите сумму" class="form-control" name="PAYMENT_AMOUNT" value="<?=$price?>" required><BR>
                                                        <input type="hidden" class="form-control" name="TOKENS" value="<?=$tokens?>" required><BR>
                                                    </div>
                                                    <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" name="PAYMENT_METHOD" value="Оплатить">


                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <h2 class="h2 mb-0">Visa/Mastercard</h2>
                                        <form class="form" action="/pay/request" method="POST">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <?
                                                    $convert = \common\models\Changes::findOne(1)['cur2'];
                                                    $price_com = $price*$convert;
                                                    $com = $price_com*0.01;
                                                    $res = $price_com + $com;
                                                    ?>
                                                    <p>К оплате: <? echo $res;?></p>
                                                    <input type="hidden" name="user_id" value="<?=$user['id']?>">
                                                    <input type="hidden" name="program" value="<?=$program?>">
                                                    <input type="hidden" name="promo" value="<?=$promo?>">

                                                    <div class="form-group mt-3">
                                                        <input type="hidden" class="form-control" name="amount" value="<?=$res?>" ><BR>
                                                        <input type="hidden" class="form-control" name="amount_usd" value="<?=$price?>" ><BR>
                                                        <input type="hidden" class="form-control" name="tokens" value="<?=$tokens?>" ><BR>
                                                    </div>
                                                    <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="Оплатить">


                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="payeer" role="tabpanel" aria-labelledby="payeer-tab">
                                        <h2 class="h2 mb-0">Payeer</h2>
                                        <form class="form" action="/payeer/request" method="POST">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <p>К оплате: <? echo $price;?></p>
                                                    <input type="hidden" name="user_id" value="<?=$user['id']?>">
                                                    <input type="hidden" name="program" value="<?=$program?>">
                                                    <input type="hidden" name="promo" value="<?=$promo?>">
                                                    <div class="form-group mt-3">
                                                        <input type="hidden" class="form-control" name="amount" value="<?=$price?>" ><BR>
                                                    </div>
                                                    <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="Оплатить">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?}?>





                </div>
            </div>

        </div>
    </main>


<?
echo \frontend\components\LoginWidget::widget();
?>