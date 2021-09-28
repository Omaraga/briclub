<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;
use yii\web\View;

$this->registerJsFile('/js/clipboard.js');
$this->registerJsFile('https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js');
$this->registerJsFile('https://yastatic.net/share2/share.js');
$this->title = $news['title'];
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
	$url2 = 'https://shanyrakplus.com/tokens/get?promo='.$user['username'];
}
$img = $news['link'];
?>
    <main class="cours">
        <div class="container">
            <div class="row">
                <main role="main" class="col-12">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center hgroup">
                        <h1 class="h1"><?=$this->title?></h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <img  src="<?=$img?>" style="border-radius: 10px;" class="card-img-top" alt="...">
                            <p class="small"><?=date('d.m.Y H:i',$news['time'])?></p>
                            <?=$news['text']?>
							<?if($news['id'] == 17){?>
								<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#promoModal">Получить ссылку на участие</a>
							<br>
							<br>
							<br>
							<br>
							<br>
							<?}?>
                        </div>
                    </div>

                </main>

            </div>
        </div>



    </main>
<div class="modal fade" id="promoModal" tabindex="-1" role="dialog" aria-labelledby="promoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Поделиться ссылкой на продажу токенов
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <span id="p2"><?=$url2?></span>
                            <button class="btn btn-link" onclick="copy('p2')">Копировать</button>
                            <h4>Поделиться в:</h4>
                            <div class="ya-share2" data-title="Промо ссылка Shanyrakplus.com" data-url="<?=$url2?>" data-services="vkontakte,facebook,twitter,viber,whatsapp,skype,telegram"></div>
                            <br>
                            <br>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?
echo \frontend\components\LoginWidget::widget();
?>