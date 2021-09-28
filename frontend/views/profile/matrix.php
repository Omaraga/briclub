<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Подтвердить личников";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');

$self = false;
$conf3 = false;
$conf = false;
$errora = false;
$errora3 = false;
if($user['id'] == Yii::$app->user->id){
    $self = true;
    $conf2 = \common\models\Confirms::find()->where(['user_id'=>$user['id']])->one();
    if(!empty($conf2)){
        $conf = true;

        if($conf2['status'] == 2){
            $errora  = true;
        }
    }
}else{
    $conf4 = \common\models\Confirms::find()->where(['user_id'=>$user['id']])->one();
    if(!empty($conf4)){
        $conf3 = true;

        if($conf4['status'] == 2){
            $errora3  = true;
        }
    }
}

$children = \common\models\User::find()->where(['parent_id'=>$user['id']])->orderBy('id asc')->all();
 ?>
    <style>
        .empty-mat{
            background: #d4d4d5!important;
        }
        .empty-mat svg{
            background: #d4d4d5!important;
        }
        .avatar-block {
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            background-color: #F2F3FF;
            padding: .5rem .5rem;
            border-radius: .25rem; }
        .avatar-block-wrap {
            padding-left: 1rem; }
        .avatar-block .avatar-icon {
            background: #258FFC;
            border-radius: 90%;
            padding: .75rem; }
        .structure {
            width: 100%;
            overflow: auto; }
        .structure-wrap {
            min-width: 800px; }
    </style>
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
    <main class="cours">
        <div class="container-fluid">
            <div class="row">
            <?/*=\frontend\components\NavWidget::widget()*/?>

                <main role="main" class="structure col-md-12">

                    <div class="structure-wrap">
                        <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center hgroup">
                            <h1 class="h1">Личники <?=$user['username']?></h1>
                        </div>
                        <div class="d-flex mb-5 justify-content-center flex-wrap flex-md-nowrap align-items-center hgroup">
                            <?if($self){?>
                                <?if(!$conf){?>
                                    <a href="/profile/matrix/confirm" class="btn btn-success">Подтвердить список</a>

                                    <a href="/profile/matrix/error" class="btn btn-danger">Неверный список</a>
                                <?}else{?>
                                    <?
                                        if($errora){?>
                                            <p>Отмечено как неверно</p>
                                        <?}else{?>
                                            <p>Список подтвержден</p>
                                        <?}
                                    ?>

                                <?}?>

                            <?}?>

                            <?if(!$self){?>
                                <a href="/profile/matrix/<?=Yii::$app->user->id?>" class="btn btn-success">Вернуться к своим личникам</a>
                                <?if(!$conf3){?>
                                    <p>Не подтвержден</p>
                                <?}else{?>
                                    <?
                                    if($errora3){?>
                                        <p>Отмечено как неверно</p>
                                    <?}else{?>
                                        <p>Список подтвержден</p>
                                    <?}
                                    ?>

                                <?}?>
                            <?}?>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4 offset-4">
                                        <div class="avatar-block mb-5">
                                            <div class="avatar-icon">
                                                <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                            </div>

                                            <div class="avatar-block-wrap">
                                                <h4 class="h4 mb-0"><?=$user['username']?></h4>
                                                <p class="p mb-0"><?=$user['fio']?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                    <div class="row">
                                            <?if(isset($children[0])){?>
                                                <div class="col-4 offset-2">
                                                    <div class="avatar-block mb-5">
                                                        <div class="avatar-icon <?if(!isset($children[0])){?>empty-mat<?}?>">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <a href="/profile/matrix/<?=$children[0]['id']?>"><h4 class="h4 mb-0"><?if(isset($children[0])){?><?=$children[0]['username']?><?}?></h4></a>
                                                            <p class="p mb-0"><?=$children[0]['fio']?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?}?>

                                            <?if(isset($children[1])){?>
                                                <div class="col-4">
                                                    <div class="avatar-block mb-5">
                                                        <div class="avatar-icon <?if(!isset($children[1])){?>empty-mat<?}?>" >
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <a href="/profile/matrix/<?=$children[1]['id']?>"><h4 class="h4 mb-0"><?if(isset($children[1])){?><?=$children[1]['username']?><?}?></h4></a>
                                                            <p class="p mb-0"><?=$children[1]['fio']?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?}?>

                                    </div>
                                </div>

                        </div>
                        <div class="row">
                            <?
                                if(count($children)>2){?>
                                    <div class="col-9 offset-1">
                                    <h1>Еще личники</h1>
                                    <?
                                    $i=0;
                                    foreach ($children as $child) {
                                        $i++;
                                        if($i<3) continue;
                                        ?>
                                        <div class="col-12">
                                            <div class="avatar-block mb-5">
                                                <div class="avatar-icon <?if(!isset($child)){?>empty-mat<?}?>" >
                                                    <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <a href="/profile/matrix/<?=$child['id']?>"><h4 class="h4 mb-0"><?if(isset($child)){?><?=$child['username']?><?}?></h4></a>
                                                    <p class="p mb-0"><?=$child['fio']?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?}
                                    ?>
                                    </div>
                                <?}
                            ?>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </main>

            </div>
        </div>



    </main>
<?
echo \frontend\components\LoginWidget::widget();
?>