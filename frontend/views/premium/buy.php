<?php

$this->title = "Premium-аккаунт";
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', ['depends' => 'yii\web\JqueryAsset']);

?>

<main class="pay__premium_2">
    <div class="container-fluid">

        <div class="row justify-content-center" >
            <div class="col-lg-7 col-xs-7 col-12">
                <h1 class="h1 text-left title">Оплата Premium-аккаунта</h1>
                <div class="block__left-info">
                    <ul class="nav" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="paycv-tab" data-toggle="tab" href="#paycv" role="tab" aria-controls="paycv" aria-selected="true">Оплата CV</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link btn__white" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Visa/Mastercard</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link btn__white" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Payeer</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link btn__white" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Perfect Money</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="paycv" role="tabpanel" aria-labelledby="paycv-tab">
                            <div class="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h2 class="h2">Ваш баланс: <?=$user['w_balans'];?> CV</h2>
                                            <h2 class="h2">К оплате: <?=$price?> CV</h2>

                                            <div class="d-grid gap-2 modale__body-button mt-3 pt-3">
                                                <a href="/premium/pay?id=<?=$premium_id?>" class="btn btn__blue">Оплатить</a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="">
                                <form class="form" action="/pay/request" method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <?
                                            $convert = \common\models\Changes::findOne(1)['cur2'];
                                            $price_com = $price*$convert;
                                            $com = $price_com*0.01;
                                            $res = $price_com + $com;
                                            ?>
                                            <input type="hidden" name="user_id" value="<?=$user['id']?>">
                                            <input type="hidden" name="program" value="<?=$program?>">
                                            <input type="hidden" name="premium_id" value="<?=$premium_id?>">

                                            <div class="form-group mt-3">
                                                <input type="hidden" class="form-control" name="amount" value="<?=$res?>" ><BR>
                                                <h2 class="h2">К оплате: <?=$res?> тг.</h2>
                                                <input type="hidden" class="form-control" name="amount_usd" value="<?=$price?>" ><BR>
                                                <input type="hidden" class="form-control" name="tokens" value="<?=$tokens?>" ><BR>
                                            </div>
                                            <div class="d-grid gap-2 modale__body-button mt-3">
                                                <input class="btn__blue btn btn__small" type="submit" value="Оплатить">
                                            </div>


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="">
                                <form class="form" action="/payeer/request" method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="hidden" name="user_id" value="<?=$user['id']?>">
                                            <input type="hidden" name="program" value="<?=$program?>">
                                            <input type="hidden" name="premium_id" value="<?=$premium_id?>">
                                            <div class="form-group mt-3">
                                                <h2 class="h2">К оплате: $<?=$price?></h2>
                                                <input type="hidden" class="form-control" name="amount" value="<?=$price?>" ><BR>
                                            </div>
                                            <div class="d-grid gap-2 modale__body-button mt-3">
                                                <input class="btn__blue btn btn__small" type="submit" value="Оплатить">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="">
                                <form class="form" action="https://perfectmoney.com/api/step1.asp" method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="hidden" name="PAYEE_ACCOUNT" value="U13740002">
                                            <input type="hidden" name="PAYEE_NAME" value="lseplatform.com">
                                            <input type="hidden" name="PAYMENT_ID" value="<?=$user['id']?>-<?=$program?>-<?=$premium_id?>">
                                            <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                            <input type="hidden" name="STATUS_URL" value="https://lseplatform.com/payment/status">
                                            <input type="hidden" name="PAYMENT_URL" value="https://lseplatform.com/payment/activ">
                                            <input type="hidden" name="NOPAYMENT_URL" value="https://lseplatform.com/payment/fail">

                                            <div class="form-group mt-3">
                                                <h2 class="h2">К оплате: $<?=$price?></h2>
                                                <input type="hidden" placeholder="Введите сумму" class="form-control" name="PAYMENT_AMOUNT" value="<?=$price?>" required><BR>
                                                <input type="hidden" class="form-control" name="TOKENS" value="<?=$tokens?>" required><BR>
                                            </div>
                                            <div class="d-grid gap-2 modale__body-button mt-3">
                                                <input class="btn__blue btn btn__small" type="submit" value="Оплатить">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-xs-6 col-12">
                <div class="block__right-fon">
                    <button class="btn__yallow w7 text-dark">
                        <img src="/img/pay__premium/coron.svg" alt="">
                        Premium
                    </button>
                    <div class="size">
                        <p class="w7 text-white mt-4">Пользуйся расширенными возможностями</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
