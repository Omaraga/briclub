<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Пополнение баланса";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}

$account1 = \common\models\DepositAccounts::findOne(1)['account'];
$account2 = \common\models\DepositAccounts::findOne(2)['account'];
$this->registerJs("$('.nav-link').click(function () {

            $('.nav-link').each(function (index) {
                $('.nav-link').addClass('btn__white')
            })
            $(this).removeClass('btn__white')
        })");

$script = <<<JS
    function copy(text){
        var aux = document.createElement("div");
        aux.setAttribute("contentEditable", true);
        aux.innerHTML = text;
        aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)");
        document.body.appendChild(aux);
        aux.focus();
        document.execCommand("copy");
        document.body.removeChild(aux);
    }
    $('#cryptoCv').keyup(function (){
        let sum = parseFloat($(this).val());
        
        if (sum > 0){
            let sumUsdt = sum + (sum * 0.005);
            $('#cryptoUsdt').val(sumUsdt + ' USDT');
            $('#criptoKomText').text(''+ sum + ' USDT + ' + (sum * 0.005) +' USDT (Комиссия 0.5%)')
        }else{
            $('#cryptoUsdt').val('');
            $('#criptoKomText').text('Комиссия 0.5%')
        }
        
    });
    $('#copyAddress').click(function (){
        let text = $('#ustdAddress').val();
        console.log(text);
        copy(text);
    });
    
JS;
$this->registerJs($script);

?>
    <main class="replenish">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <div class="d-flex align-items-center">
                            <img src="/img/payment/arrow.svg" alt="">
                            <h1 class="h1 w7 text ml-3">Покупка CV</h1>
                        </div>
                    </div>

                    <div class="col-lg-11 mt-4">
                        <div class="block__fon block-cv mb-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <img src="\img/main/bCV.svg" alt="">
                                    <span class="w5">Баланс</span>
                                </div>
                                <img src="\img/replenish/CV.svg" alt="">
                            </div>
                            <div class="mt-5">
                                <span class="h4 w7 mr-2">CV</span>
                                <span class="h2 w7"><?=$user['w_balans'];?></span>
                            </div>
                        </div>
                        <ul class="nav nav-tabs mt-3 mb-3" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link btn__blue btn__small active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">PerfectMoney</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn__blue btn__white" id="home-tab" data-toggle="tab" href="#visa" role="tab" aria-controls="home" aria-selected="true">Visa/Mastercard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn__blue btn__white" id="yandex-tab" data-toggle="tab" href="#yandex" role="tab" aria-controls="home" aria-selected="true">Payeer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn__blue btn__white" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Advcash</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn__blue btn__white" id="alfa-tab" data-toggle="tab" href="#alfa" role="tab" aria-controls="profile" aria-selected="false">Альфабанк</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn__blue btn__white" id="cripto-tab" data-toggle="tab" href="#cripto" role="tab" aria-controls="profile" aria-selected="false">Криптовалюта</a>
                            </li>
                        </ul>
                        <div class="tab-content mb-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <h2 class="h2 mt-5">PerfectMoney</h2>
                                <form class="form" action="https://perfectmoney.com/api/step1.asp" method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <input type="hidden" name="PAYEE_ACCOUNT" value="U13740002">
                                            <input type="hidden" name="PAYEE_NAME" value="Lseplatform.com">
                                            <input type="hidden" name="PAYMENT_ID" value="<?=$user['id']?>"><BR>

                                            <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                            <input type="hidden" name="STATUS_URL" value="https://lseplatform.com/payment/status">
                                            <input type="hidden" name="PAYMENT_URL" value="https://lseplatform.com/payment/activ">
                                            <input type="hidden" name="NOPAYMENT_URL" value="https://lseplatform.com/payment/fail">
                                            <div class="form-group mt-3">
                                                <input type="text" placeholder="Введите сумму CV" class="form-control" name="PAYMENT_AMOUNT" value="" required><BR>
                                            </div>
                                            <input class="btn btn__small btn__blue button mt-4 col-md-8 mb-5" type="submit" name="PAYMENT_METHOD" value="Пополнить">


                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="yandex" role="tabpanel" aria-labelledby="yandex-tab">
                                <h2 class="h2 mt-5">Payeer</h2>
                                <form class="form" action="/payeer/request" method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <input type="hidden" name="user_id" value="<?=$user['id']?>">
                                            <div class="form-group mt-3">
                                                <input type="text" class="form-control" placeholder="Введите сумму CV" name="amount" ><BR>
                                            </div>
                                            <input class="btn btn__small btn__blue button mt-4 col-md-8 mb-5" type="submit" value="Пополнить">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="visa" role="tabpanel" aria-labelledby="profile-tab">

                                <h2 class="h2 mt-5">Visa/Mastercard</h2>
                                <form class="form" action="/pay/request" method="POST">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <input type="hidden" name="user_id" value="<?=$user['id']?>">
                                            <div class="form-group mt-3">
                                                <input type="text" class="form-control" placeholder="Введите сумму CV" name="amount" ><BR>
                                            </div>
                                            <input class="btn btn__small btn__blue button mt-4 col-md-8 mb-5" type="submit" value="Пополнить">


                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h2 class="h2 mt-5 ">Advcash</h2>
                                <p>
                                    <?=$account1?>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="alfa" role="tabpanel" aria-labelledby="profile-tab">
                                <h2 class="h2 mt-5">Альфабанк</h2>
                                <p><?=$account2?></p>
                            </div>
                            <div class="tab-pane fade " id="cripto" role="tabpanel" aria-labelledby="profile-tab">
                               <div class="replenish">
                                   <p class="mb-4 w5">Уважаемые пользователи,<br>спешим сообщить Вам об улучшениях на нашей платформе!</p>
                                   <p class="w5">Теперь дополнительно, появилась возможность пополнять баланс через любой удобный <br> вам Ethereum кошелек (с помощью криптовалюты USDT).  </p>

                                   <div class="col-12 mt-4 col-md-7" style="margin-left: -15px">
                                       <label class="w5" for="cryptoCv">Введите сумму пополнение</label><br>
                                       <input type="text" class="input form-control" id="cryptoCv" placeholder="Сумма в CV">
                                   </div>
                                   <div class="mt-4 col-12 mb-5 col-md-7" style="margin-left: -15px">
                                       <label class="w5" for="cryptoUsdt">Сумма которую необходимо перевести</label><br>
                                       <input type="text" class="input form-control" id="cryptoUsdt" placeholder="" disabled>
                                       <p class="w5 text__small" id="criptoKomText"></p>
                                   </div>

                                   <div class="opl">
                                       <div class="baner mt-5">
                                           <div class="mb-3">
                                               <p class="m-0 mb-2">1. Вам необходимо нужную сумму + 0.5% комиссии отправить на</p>
                                               <div class="d-flex">
                                                   <input type="text" id="ustdAddress" class="form-control col-11 col-md-6 text__small" style="background: #283858; color:yellow" value="0x20B9Cf2a5Eb2B88428088f021A39744696CE0fD4" disabled>
                                                   <a class="btn" id="copyAddress"><i class="fa fa-files-o" aria-hidden="true"></i></i></a>
                                               </div>
                                           </div>
                                           <p class="m-0">2. После пополнения, необходимо скриншот транзакции отправить на Whatsapp <span style="color:yellow">+7-705-368-80-16</span>. НЕ ЗАБУДЬТЕ указать ЛОГИН аккаунта пополняемого кабинета!!! </p>
                                       </div>
                                   </div>
                                   <p class="mt-4">Так же хотим обратить ваше внимание, что по воскресеньям пополнения не начисляются, все пополнения, сделанные в воскресенье, будут начислены в понедельник!
                                   </p>
                                   <p>Спасибо что Вы с нами!</p>
                                   <p class="w5">С уважением, Техгруппа.</p>
                               </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>