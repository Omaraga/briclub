<?php

/* @var $this yii\web\View */

use common\models\ShanyrakUser;
use yii\httpclient\Client;


$this->title = "Shanyrak";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$this->registerJs('
   
');
 ?>
<?
$flashes = Yii::$app->session->allFlashes;
if(!empty($flashes)){
    foreach ($flashes as $key => $flash) {?>
        <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
            <?=$flash?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?}}
?>
    <main class="shanyrak">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Shanyrak</h1>
                <div class="hline"></div>
            </div>

            <section class="description">
                <p><?=$programs[0]['description']?></p>
                <a href="/docs/shanyrak.pdf" class="btn btn-success">Скачать презентацию</a>
            </section>

            <section class="shanyrak-program-type">
                <div class="row">
                    <? foreach ($programs as $program) {
                        if($program['id'] == 4) continue;
                        $program_user = ShanyrakUser::find()->where(['user_id'=>$user['id'],'program_id'=>$program['id']])->one();
                        ?>
                        <div class="col-lg-4">
                            <div class="card">
                                <img src="<?=$program['link']?>" class="card-img-top" alt="...">
                                <? if(!empty($program_user)){?>

                                        <?
                                        if($program_user['status'] == 2){
                                            if($program_user['step'] == 1){?>
                                                <div class="card-body">
                                                    <h5 class="card-title h4"><?=$program['title']?></h5>
                                                    <div class="status"><span class="badge badge-primary badge-pill">Шаг <?=$program_user['step']+1?></span></div>
                                                    <p class="card-text">Заполните заявку</p>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="/shanyrak/start?program=<?=$program['id']?>" class="btn btn-success">Заполнить</a>
                                                </div>
                                            <?}elseif ($program_user['step'] == 2){?>
                                                <div class="card-body">
                                                    <h5 class="card-title h4"><?=$program['title']?></h5>

                                                    <div class="status"><span class="badge badge-primary badge-pill">Шаг <?=$program_user['step']+1?></span></div>
                                                    <p class="card-text">Подпишите договор</p>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="card-footer">
                                                        <a href="/shanyrak/start?program=<?=$program['id']?>" class="btn btn-success">Подписать</a>
                                                    </div>
                                                </div>
                                            <?}elseif ($program_user['step'] == 3){?>
                                                <div class="card-body">
                                                    <h5 class="card-title h4"><?=$program['title']?></h5>
                                                    <div class="status"><span class="badge badge-primary badge-pill">Шаг <?=$program_user['step']+1?></span></div>
                                                    <p class="card-text">Внесите первый платеж</p>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="/shanyrak/start?program=<?=$program['id']?>" class="btn btn-success">Внести</a>
                                                </div>
                                            <?}
                                        }else{?>
                                            <div class="card-body">
                                                <h5 class="card-title h4"><?=$program['title']?></h5>
                                                <p class="card-text">Оплатите ежемесячный платеж</p>
                                            </div>
                                            <div class="card-footer">
                                                <a href="/shanyrak/program?id=<?=$program['id']?>" class="btn btn-success">Оплатить</a>
                                            </div>
                                        <?}?>
                                <?}else{?>
                                    <div class="card-body">
                                        <h5 class="card-title h4"><?=$program['title']?></h5>
                                        <p class="card-text"><?=$program['description']?></p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="/shanyrak/start?program=<?=$program['id']?>" class="btn btn-primary">Участвовать</a>
                                        <?
                                            if($program['id'] == 5){
                                                $doc = "/docs/ned.pdf";
                                            }elseif($program['id'] == 6){
                                                $doc = "/docs/auto.pdf";
                                            }elseif($program['id'] == 7){
                                                $doc = "/docs/tech.pdf";
                                            }
                                        ?>
                                        <a href="<?=$doc?>"  class="btn btn-link">Подробнее</a>
                                    </div>

                                <?}?>
                            </div>
                        </div>
                    <?}?>
                </div>
            </section>




        </div>
    </main>


<?
echo \frontend\components\LoginWidget::widget();
?>