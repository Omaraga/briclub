<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->title = 'Пополнение';
?>

<div class="container">
<div class="header_h">
    <h2 class="w6">Пополнение баланса</h2>
</div>
<div class="bg-black">
    <div>
        <h3><span style=" font-weight:bold">Баланс: CV <?=$user['w_balans'];?></span></h3>
    </div>
    <div class="nav btn-group">
        <ul class="nav nav-tabs mt-3 mb-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link btn-dark active" id="home-tab" data-toggle="tab" href="#home" >Perfect Money</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-dark " id="yandex-tab" data-toggle="tab" href="#yandex" >Payeer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-dark " id="home-tab" data-toggle="tab" href="#visa" >Visa/MasterCard</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form class="form" action="https://perfectmoney.com/api/step1.asp" method="POST">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" name="PAYEE_ACCOUNT" value="U13740002">
                            <input type="hidden" name="PAYEE_NAME" value="Lseplatform.com">
                            <input type="hidden" name="PAYMENT_ID" value="<?=$user['id']?>">
                            <input type="hidden" name="PAYMENT_UNITS" value="USD">
                            <input type="hidden" name="STATUS_URL" value="https://lseplatform.com/payment/status">
                            <input type="hidden" name="PAYMENT_URL" value="https://lseplatform.com/payment/activ">
                            <input type="hidden" name="NOPAYMENT_URL" value="https://lseplatform.com/payment/fail">
                            <div class="block block-width">
                                <input type="text" class="input_padding" placeholder="Введите сумму" class="form-control" name="PAYMENT_AMOUNT" value="" required>
                                <input class="btn btn-padding" type="submit" name="PAYMENT_METHOD" value="Пополнить">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="yandex" role="tabpanel" aria-labelledby="yandex-tab">
                <form class="form" action="/payeer/request" method="POST">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" name="user_id" value="<?=$user['id']?>">
                            <div class="block block-width">
                                <input type="text" class="input_padding" placeholder="Введите сумму CV" name="amount" >
                                <input class="btn btn-padding" type="submit" value="Пополнить">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="visa" role="tabpanel" aria-labelledby="profile-tab">
                <form class="form" action="/pay/request" method="POST">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" name="user_id" value="<?=$user['id']?>">
                            <div class="block block-width">
                                <input type="text" class="input_padding" placeholder="Введите сумму CV" name="amount" >
                                <input class="btn btn-padding" type="submit" value="Пополнить">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>