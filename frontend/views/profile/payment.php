<?php
use common\models\User;
$this->title = "Оплата системы";
$user = User::findOne(Yii::$app->user->identity['id']);


?>
<main class="payment d-flex">
    <div class="payment-block">
        <h4 class="w7 margin-bot-50">Оплата титула клуба <span class="txt-green-100">Резидент</span> </h4>
        <div class="block-img">
            <div class="top">
                <h3 class="w7"><?=$user->w_balans?> PV</h3>
                <h5 class="margin-bot-50 ml-1">Титул клуба: <span>Резидент</span></h5>
            </div>
            <div class="body">
                <ul class="nav nav-pills mb-3 margin-bot-50" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fon-gray-300 active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Криптовалюта</a>
                    </li>
                    <li class="nav-item ml-3" role="presentation">
                        <a class="nav-link fon-gray-300" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">CV</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <a href="\profile/activ?program=2">Оплатить</a>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <a href="\profile/activ?program=2">Оплатить</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>