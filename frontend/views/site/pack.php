<section class="skill-point" id="s-<?=$id?>">
    <div class="container">
        <div class="skill-point-block">
            <div class="skill-point-block-wrap">
                <h4 class="h4"><?=$data['title']?></h4>
                <div class="skill-point-wrapper">
                    <?
                    foreach ($data['tab_text'] as $datum) {
                        ?>
                        <span class="skill-point-wrapper-item"><?=$datum['title']?></span>
                        <?}?>
                </div>
            </div>
            <div class="skill-point-block-wrap-info-about-video">
                <div class="info-about-video">
                    <?php if(!empty($data['price'])){?>
                        <?if(!\common\models\UserCourses::isAccess(Yii::$app->user->identity['id'],$data['course_id'])){?>
                            <button id="bedbtn2" class="btn btn-purchesse" data-toggle="modal" onsubmit="ga('send', 'event', 'form', 'submit');" onclick="ga('send', 'event', 'button', 'click');" data-target="#bedModal"><span class="icon-block"><img src="/img/caticon.svg" alt="" class="icon-block-cat"></span>Купить <?=$data['type_text']?></button>
                        <?}else{?>
                            <a class="btn btn-video"  href="/course/<?=$data['course_id']?>"><span class="icon-block"><img src="/img/play-btn-icon.svg" alt="" class="icon-block-cat"></span>Смотреть <?=$data['type_text']?></a>
                        <?}?>
                    <?}else{?>
                        <a class="btn btn-video" <?if(Yii::$app->user->isGuest){?>data-toggle="modal" data-target="#letauthModal" href="#"<?}else{?>href="/course/<?=$data['course_id']?>" <?}?> ><span class="icon-block"><img src="/img/play-btn-icon.svg" alt="" class="icon-block-cat"></span>Смотреть <?=$data['type_text']?></a>
                    <?}?>
                    <span class="info-about-video-item"><span class="name">Стоимость:</span><span class="price"><?php if(!empty($data['price'])){echo $data['price'];?><span class="curent">тг.</span><?}else{echo "Бесплатно";}  ?></span><?php if(!empty($data['old_price'])){?><del class="old-price"><?=$data['old_price']?> <span class="curent">тг.</span></del><?}?></span>
                    <span class="info-about-video-item"><span class="name">Длительность:</span><span class="time"><?=$data['duration']?></span></span>
                </div>
            </div>
            <div class="skill-point-certificate">
                <div class="skill-point-certificate-wrap">
                    <div class="text-group">
                        <h4 class="h4">Сертификат Oneroom</h4>
                        <p>Подтверждает успешное прохождение курса <br>«<?=$data['course_title']?>»</p>
                    </div>
                    <div class="certificate"></div>
                </div>
            </div>
        </div>
    </div>
</section>