<?
if(!Yii::$app->user->isGuest){
    
}

$this->registerJs("
pay = function () {
    var widget = new cp.CloudPayments();
    widget.charge({ // options
            publicId: 'pk_e20442adc77519a57bf082958b2ac',  //id из личного кабинета
            description: 'Пример оплаты (деньги сниматься не будут)', //назначение
            amount: ".$data['price'].", //сумма
            currency: 'KZT', //валюта
            invoiceId: '1234567', //номер заказа  (необязательно)
            accountId: '".Yii::$app->user->identity['email']."', //идентификатор плательщика (необязательно)
            skin: \"mini\", //дизайн виджета
            data: {
                
            }
        },
        function (options) { // success
            console.log(options);
        },
        function (reason, options) { // fail
            //действие при неуспешной оплате
        });
};
$('#checkout').click(pay);
");
?>
<section class="landing" id="s-<?=$id?>">
    <div class="container">
        <? if($data['type'] == 1){?>
            <span class="landing-leasson-type course">Онлайн курс</span>
        <?}elseif($data['type'] == 2){ ?>
            <span class="landing-leasson-type video">Видео</span>
        <?}elseif($data['type'] == 3){ ?>
            <span class="landing-leasson-type online-workshop">Онлайн семинар</span>
        <?}elseif($data['type'] == 4){ ?>
            <span class="landing-leasson-type online-workshop">Онлайн воркшоп</span>
        <?} ?>

        <div class="row">
            <div class="col-12">
                <h1 class="h1"><?=$data['title']?></h1>
            </div>
            <div class="col-lg-6 col-md-8 col-12">
                <p><?=$data['des']?></p>
            </div>
        </div>
        <div class="info-about-video">
            <?php if(!empty($data['price'])){?>
                <?if(!\common\models\UserCourses::isAccess(Yii::$app->user->identity['id'],$data['course_id'])){?>
                    <button id="bedbtn" class="btn btn-purchesse" data-toggle="modal" onsubmit="ga('send', 'event', 'form', 'submit');" onclick="ga('send', 'event', 'button', 'click');" data-target="#bedModal"><span class="icon-block"><img src="/img/caticon.svg" alt="" class="icon-block-cat"></span>Купить <?=$data['type_text']?></button>
                <?}else{?>
                    <a class="btn btn-video"  href="/course/<?=$data['course_id']?>"><span class="icon-block"><img src="/img/play-btn-icon.svg" alt="" class="icon-block-cat"></span>Смотреть <?=$data['type_text']?></a>
                <?}?>
            <?}else{?>
                <a class="btn btn-video" <?if(Yii::$app->user->isGuest){?>data-toggle="modal" data-target="#letauthModal" href="#"<?}else{?>href="/course/<?=$data['course_id']?>" <?}?> ><span class="icon-block"><img src="/img/play-btn-icon.svg" alt="" class="icon-block-cat"></span>Смотреть <?=$data['type_text']?></a>
            <?}?>
            <?
            $free = false;

            $lessons = \common\models\Lessons::find()->where(['course_id'=>$data['course_id']])->all();
            foreach ($lessons as $lesson) {
                if($lesson['free'] == 1){
                    $free = true;
                    continue;
                }
            }

            if($free){?>
                <a class="btn btn-video" href="/course/<?=$data['course_id']?>" ><span class="icon-block"><img src="/img/play-btn-icon.svg" alt="" class="icon-block-cat"></span>Пробный урок</a>
            <?}
            ?>
            <span class="info-about-video-item"><span class="name">Стоимость:</span><span class="price"><?php if(!empty($data['price'])){echo $data['price'];?><span class="curent">тг.</span><?}else{echo "Бесплатно";}  ?></span><?php if(!empty($data['old_price'])){?><del class="old-price"><?=$data['old_price']?> <span class="curent">тг.</span></del><?}?></span>
            <span class="info-about-video-item"><span class="name">Длительность:</span><span class="time"><?=$data['duration']?></span></span>
        </div>
        <div class="video-block" style="background: url(<?=$data['img']?>) no-repeat center;">
            <a href="#1" class="video-block-cover-bg"></a>
            <div class="play-wrap">
                <a href="#2" class="btn-play stretched-link">
                    <img src="/img/play-img.svg" alt="" class="btn-play-img">
                </a>
                <span class="type-video">Интро ролик</span>
            </div>
        </div>
    </div>
</section>