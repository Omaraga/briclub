<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Выберите способ оплаты";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
if($user['id'] == 21073){
    $price = 1;
}

?>
    <main class="transaction">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Выберите способ оплаты</h1>
                <h5 class="h5">Shanyrak</h5>
                <h5 class="h5">Стоимость: <?=$price?> CV</h5>
                <div class="hline"></div>
            </div>

            <div class="row">
                <div class="col-lg-10">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Оплатить с баланса</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="perfect-tab" data-toggle="tab" href="#perfect" role="tab" aria-controls="home" aria-selected="true">PerfectMoney</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Visa/Mastercard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#payeer" role="tab" aria-controls="payeer" aria-selected="false">Payeer</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h2 class="h2 mb-0">Оплатить с баланса</h2>
                            <p>Ваш баланс: <?=$user['w_balans']?></p>
                            <form class="form" action="/profile/activ" method="GET">
                                <div class="row">
                                    <div class="col-lg-6">
                                            <div class="form-group mt-3">
                                                <input type="hidden" placeholder="Введите сумму" class="form-control" name="program" value="<?=$program?>">
                                                <input type="text" placeholder="Введите сумму" class="form-control" name="sum" value="<?=$price?>" required disabled><BR>
                                            </div>
                                            <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" name="PAYMENT_METHOD" value="Оплатить">


                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="perfect" role="tabpanel" aria-labelledby="perfect-tab">
                            <h2 class="h2 mb-0">PerfectMoney</h2>
                            <form class="form" action="https://perfectmoney.com/api/step1.asp" method="POST">
                                <div class="row">
                                    <div class="col-lg-6">
                                            <p>К оплате: <? echo $price;?></p>
                                            <input type="hidden" name="PAYEE_ACCOUNT" value="U13740002">
                                            <input type="hidden" name="PAYEE_NAME" value="Shanyrakplus.com">
                                            <input type="hidden" name="PAYMENT_ID" value="<?=$user['id']?>-<?=$program?>">
                                            <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                            <input type="hidden" name="STATUS_URL" value="https://shanyrakplus.com/payment/status">
                                            <input type="hidden" name="PAYMENT_URL" value="https://shanyrakplus.com/payment/activ">
                                            <input type="hidden" name="NOPAYMENT_URL" value="https://shanyrakplus.com/payment/fail">

                                            <div class="form-group mt-3">
                                                <input type="hidden" placeholder="Введите сумму" class="form-control" name="PAYMENT_AMOUNT" value="<?=$price?>" required><BR>
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

                                        <div class="form-group mt-3">
                                            <input type="hidden" class="form-control" name="amount" value="<?=$res?>" ><BR>
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

        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>