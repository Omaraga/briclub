<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;
use yii\data\Pagination;
//use yii\widgets\LinkPager;
use yii\bootstrap4\LinkPager;

$this->title = "Активность";
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$tab = Yii::$app->request->get('tab');
if (!isset($tab)){
    $tab = 0;
}
$tab = intval($tab);

if ($tab == 0){
    $query = \common\models\Actions::find()->where(['user_id'=>$user['id']])->orderBy('id desc');
    $actionsSize = \common\models\Actions::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->count();
}else{
    $typeList = array();
    if ($tab == 1){
        $typeList = [1,8,7,77,88,9,10,11];
    }else if($tab == 2) {
        $typeList = [105];
    }else if($tab == 3){
        $typeList = [4,5,6];
    }else if($tab == 4){
        $typeList = [3];
    }else {
        $typeList = [104];
    }
    $query = \common\models\Actions::find()->where(['user_id'=>$user['id']])->andWhere(['type'=>$typeList])->orderBy('id desc');
    $actionsSize = \common\models\Actions::find()->where(['user_id'=>$user['id']])->andWhere(['type'=>$typeList])->orderBy('id desc')->count();

}
$pages = new Pagination(['totalCount' => $actionsSize]);
$actions = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();


$withdraws = \common\models\Withdraws::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->all();

function getParams($obj, $class){
    $color = null;
    $text = null;
    if (isset($obj['type'])){
        $type = \common\models\ActionTypes::findOne($obj['type']);
        if($type['minus'] == 1){
            $img = "buy.svg";
            $class_2 = "output";
        }else{
            $img = "replenish.svg";
            $class_2 = "replenishment";
        }
        if($type['id'] == 3){
            $img = "transfer.svg";
            $class_2 = "transfer";
        }
        if($type['cat'] == 1){
            $class = "structure-notificate";
        }elseif($type['cat'] == 2){
            $class = "transaction-notificate";
        }elseif($type['cat'] == 3){
            $class = "transaction-notificate overdraft";
        }elseif($type['cat'] == 4){
            $class = "any-notificate";
        }elseif($type['cat'] == 5){
            $class = "message-notificate";
        }
    }else{
        $img = "buy.svg";
        $class_2 = "output";
    }

    if($obj['status'] == 3){
        $color = "yellow";
        $text = "В обработке";
    }elseif($obj['status'] == 2){
        $color = "red";
        $text = "Отменено";
    }elseif($obj['status'] == 1){
        $color = "green";
        $text = "Выполнено";
    }
    return [
        'class_2' => isset($class_2)?$class_2:"",
        'class' => $class,
        'color' => $color,
        'text' => $text,
        'img' => $img,
    ];
}


?>
    <main class="activition activity">
        <div class="container block__main-header">
            <h1 class="h1">Активность</h1>

            <div class="row">
                <div class="col-lg-10">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" data-tab="<?=$tab;?>">
                        <li class="nav-item">
                            <a class="nav-link <?=($tab==0)?'active':''?>"  href="/profile/actions">Все</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?=($tab==1)?'active':''?>"  href="/profile/actions?tab=1">Матрицы</a>
                        </li>
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" id="token-tab" data-toggle="tab" href="#token" role="tab" aria-controls="profile" aria-selected="false">Токены</a>-->
<!--                        </li>-->
                        <li class="nav-item">
                            <a class="nav-link <?=($tab==2)?'active':''?>"  href="/profile/actions?tab=2">Кураторские</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?=($tab==3)?'active':''?>"   href="/profile/actions?tab=3" >Поступления</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?=($tab==4)?'active':''?>" href="/profile/actions?tab=4" >Переводы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?=($tab==5)?'active':''?>"  href="/profile/actions?tab=5">Выводы</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-1 block__body" id="myTabContent">
                        <div class="tab-pane fade <?=($tab==0)?'show active':''?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="col-lg-12">

                                <?
                                $i = 0;
                                foreach ($actions as $withdraw) {
                                    $i++;
                                    $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                    $class = "any-notificate";
                                    if($withdraw['type'] == 105 || $withdraw['type'] == 7) {
                                        $curw = "PV";
                                    }
                                    else{
                                        $curw = "CV";
                                    }
                                    $sum = $withdraw['sum'];
                                    if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
                                        $curw = "GRC";
                                        $sum = $withdraw['tokens'];
                                    }
                                    $withdrawParams = getParams($withdraw, $class);
                                    ?>
                                    <div class="text-left">
                                        <div class="row">
                                            <div class="col-12">
                                                <ul class="row list__group text__small">
                                                    <li class="col-md-1 d-none d-lg-block"><img src="/img/activity/<?=$withdrawParams['img'];?>" alt=""></li>
                                                    <li class="col-md-3 w5 d-none d-lg-block"><?=$type['title']?></li>
                                                    <li class="col-md-3 d-none d-lg-block"><?
                                                        echo $withdraw['title'];
                                                        if($withdraw['type'] == 3){
                                                            echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                        }elseif($withdraw['type'] == 4){
                                                            echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                        }
                                                        ?></li>
                                                    <li class="col-md-1 d-none d-lg-block"><?=date("d.m.Y H:i", $withdraw['time'])?></li>
                                                    <li class="col-md-2 d-none d-lg-block text-end">
                                                        <div class="d-flex align-item-cente">
                                                            <div class="circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></div>
                                                        </div>
                                                    </li>
                                                    <li class="col-md-2 w7 d-none d-lg-block green">
                                                        <?if(!empty($sum)):?>

                                                            <?if($type['minus'] == 1):?>
                                                                <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                            <?else:?>
                                                                <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                            <?endif;?>


                                                        <?endif;?>
                                                    </li>
                                                    <div class="col-md-12 d-md-block d-lg-none">
                                                        <div class="block__body-mobil">
                                                            <div class="d-flex align-item-center">
                                                                <img src="/img/activity/buy.svg" alt="">
                                                                <div class="text__group ml-3">
                                                                    <span class="text__small w7"><?=$type['title']?></span>
                                                                    <span class="text__small"><?
                                                                        echo $withdraw['title'];
                                                                        if($withdraw['type'] == 3){
                                                                            echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                        }elseif($withdraw['type'] == 4){
                                                                            echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                        }
                                                                        ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="text__group text-end">
                                                                <span class="green w5"><?if(!empty($sum)):?>

                                                                        <?if($type['minus'] == 1):?>
                                                                            <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?else:?>
                                                                            <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?endif;?>


                                                        <?endif;?></span>
                                                                <span><?=date("d.m.Y H:i", $withdraw['time'])?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                <?}?>
                            </div>
                        </div>
                        <div class="tab-pane fade <?=($tab==1)?'show active':''?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="col-lg-12">
                                <?
                                $i = 0;
                                foreach ($actions as $withdraw) {
                                    if($withdraw['type'] == 1 or $withdraw['type'] == 8 or $withdraw['type'] == 7 or $withdraw['type'] == 77 or $withdraw['type'] == 88 or $withdraw['type'] == 9 or $withdraw['type'] == 10 or $withdraw['type'] == 11){
                                        $i++;
                                        $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                        $class = "any-notificate";
                                        if($withdraw['type'] == 105 || $withdraw['type'] == 7) {
                                            $curw = "PV";
                                        }
                                        else{
                                            $curw = "CV";
                                        }
                                        $sum = $withdraw['sum'];
                                        if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
                                            $curw = "GRC";
                                            $sum = $withdraw['tokens'];
                                        }
                                        $withdrawParams = getParams($withdraw, $class);
                                        ?>
                                        <div class="text-left">
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="row list__group text__small">
                                                        <li class="col-md-1 d-none d-lg-block"><img src="/img/activity/<?=$withdrawParams['img'];?>" alt=""></li>
                                                        <li class="col-md-3 w5 d-none d-lg-block"><?=$type['title']?></li>
                                                        <li class="col-md-3 d-none d-lg-block"><?
                                                            echo $withdraw['title'];
                                                            if($withdraw['type'] == 3){
                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }elseif($withdraw['type'] == 4){
                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }
                                                            ?></li>
                                                        <li class="col-md-1 d-none d-lg-block"><?=date("d.m.Y H:i", $withdraw['time'])?></li>
                                                        <li class="col-md-2 d-none d-lg-block text-end">
                                                            <div class="d-flex align-item-cente">
                                                                <div class="circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></div>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-2 w5 d-none d-lg-block green">
                                                            <?if(!empty($sum)):?>

                                                                <?if($type['minus'] == 1):?>
                                                                    <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?else:?>
                                                                    <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?endif;?>


                                                            <?endif;?>
                                                        </li>
                                                        <div class="col-md-12 d-md-block d-lg-none">
                                                            <div class="block__body-mobil">
                                                                <div class="d-flex align-item-center">
                                                                    <img src="/img/activity/buy.svg" alt="">
                                                                    <div class="text__group ml-3">
                                                                        <span class="text__small w7"><?=$type['title']?></span>
                                                                        <span class="text__small"><?
                                                                            echo $withdraw['title'];
                                                                            if($withdraw['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }elseif($withdraw['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }
                                                                            ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="text__group text-end">
                                                                <span class="green w5"><?if(!empty($sum)):?>

                                                                        <?if($type['minus'] == 1):?>
                                                                            <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?else:?>
                                                                            <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?endif;?>


                                                        <?endif;?></span>
                                                                    <span><?=date("d.m.Y H:i", $withdraw['time'])?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?}?>
                                <?}?>


                            </div>
                        </div>
                        <div class="tab-pane fade" id="token" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="col-lg-12">

                                <?
                                $i = 0;
                                foreach ($actions as $withdraw) {
                                    if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62){
                                        $i++;
                                        $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                        $class = "any-notificate";
                                        if($withdraw['type'] == 105 || $withdraw['type'] == 7) {
                                            $curw = "PV";
                                        }
                                        else{
                                            $curw = "CV";
                                        }
                                        $sum = $withdraw['sum'];
                                        if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
                                            $curw = "GRC";
                                            $sum = $withdraw['tokens'];
                                        }
                                        $withdrawParams = getParams($withdraw, $class);
                                        ?>
                                        <div class="text-left">
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="row list__group text__small">
                                                        <li class="col-md-1 d-none d-lg-block"><img src="/img/activity/<?=$withdrawParams['img'];?>" alt=""></li>
                                                        <li class="col-md-3 w5 d-none d-lg-block"><?=$type['title']?></li>
                                                        <li class="col-md-3 d-none d-lg-block"><?
                                                            echo $withdraw['title'];
                                                            if($withdraw['type'] == 3){
                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }elseif($withdraw['type'] == 4){
                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }
                                                            ?></li>
                                                        <li class="col-md-1 d-none d-lg-block"><?=date("d.m.Y H:i", $withdraw['time'])?></li>
                                                        <li class="col-md-2 d-none d-lg-block text-end">
                                                            <div class="d-flex align-item-cente">
                                                                <div class="circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></div>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-2 w5 d-none d-lg-block green">
                                                            <?if(!empty($sum)):?>

                                                                <?if($type['minus'] == 1):?>
                                                                    <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?else:?>
                                                                    <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?endif;?>


                                                            <?endif;?>
                                                        </li>
                                                        <div class="col-md-12 d-md-block d-lg-none">
                                                            <div class="block__body-mobil">
                                                                <div class="d-flex align-item-center">
                                                                    <img src="/img/activity/buy.svg" alt="">
                                                                    <div class="text__group ml-3">
                                                                        <span class="text__small w7"><?=$type['title']?></span>
                                                                        <span class="text__small"><?
                                                                            echo $withdraw['title'];
                                                                            if($withdraw['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }elseif($withdraw['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }
                                                                            ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="text__group text-end">
                                                                <span class="green w5"><?if(!empty($sum)):?>

                                                                        <?if($type['minus'] == 1):?>
                                                                            <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?else:?>
                                                                            <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?endif;?>


                                                        <?endif;?></span>
                                                                    <span><?=date("d.m.Y H:i", $withdraw['time'])?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?}?>
                                <?}?>


                            </div>
                        </div>
                        <div class="tab-pane fade <?=($tab==2)?'show active':''?>" id="binars" role="tabpanel" aria-labelledby="binars-tab">
                            <div class="col-lg-12">

                                <?
                                $i = 0;
                                foreach ($actions as $withdraw) {
                                    if($withdraw['type'] == 105){
                                        $i++;
                                        $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                        $class = "any-notificate";
                                        $curw = "PV";
                                        $sum = $withdraw['sum'];
                                        if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
                                            $curw = "GRC";
                                            $sum = $withdraw['tokens'];
                                        }
                                        $withdrawParams = getParams($withdraw, $class);
                                        ?>
                                        <div class="text-left">
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="row list__group text__small">
                                                        <li class="col-md-1 d-none d-lg-block"><img src="/img/activity/<?=$withdrawParams['img'];?>" alt=""></li>
                                                        <li class="col-md-3 w5 d-none d-lg-block"><?=$type['title']?></li>
                                                        <li class="col-md-3 d-none d-lg-block"><?
                                                            echo $withdraw['title'];
                                                            if($withdraw['type'] == 3){
                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }elseif($withdraw['type'] == 4){
                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }
                                                            ?></li>
                                                        <li class="col-md-1 d-none d-lg-block"><?=date("d.m.Y H:i", $withdraw['time'])?></li>
                                                        <li class="col-md-2 d-none d-lg-block text-end">
                                                            <div class="d-flex align-item-cente">
                                                                <div class="circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></div>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-2 w5 d-none d-lg-block green">
                                                            <?if(!empty($sum)):?>

                                                                <?if($type['minus'] == 1):?>
                                                                    <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?else:?>
                                                                    <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?endif;?>


                                                            <?endif;?>
                                                        </li>
                                                        <div class="col-md-12 d-md-block d-lg-none">
                                                            <div class="block__body-mobil">
                                                                <div class="d-flex align-item-center">
                                                                    <img src="/img/activity/buy.svg" alt="">
                                                                    <div class="text__group ml-3">
                                                                        <span class="text__small w7"><?=$type['title']?></span>
                                                                        <span class="text__small"><?
                                                                            echo $withdraw['title'];
                                                                            if($withdraw['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }elseif($withdraw['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }
                                                                            ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="text__group text-end">
                                                                <span class="green w5"><?if(!empty($sum)):?>

                                                                        <?if($type['minus'] == 1):?>
                                                                            <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?else:?>
                                                                            <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?endif;?>


                                                        <?endif;?></span>
                                                                    <span><?=date("d.m.Y H:i", $withdraw['time'])?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?}?>
                                <?}?>


                            </div>
                        </div>
                        <div class="tab-pane fade <?=($tab==3)?'show active':''?>" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="col-lg-12">

                                <?
                                $i = 0;
                                foreach ($actions as $withdraw) {
                                    if($withdraw['type'] == 4 or $withdraw['type'] == 5 or $withdraw['type'] == 6){
                                        $i++;
                                        $curw = "CV";
                                        $sum = $withdraw['sum'];
                                        if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
                                            $curw = "GRC";
                                            $sum = $withdraw['tokens'];
                                        }
                                        $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                        $class = "any-notificate";
                                        $withdrawParams = getParams($withdraw, $class);

                                        ?>

                                        <div class="text-left">
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="row list__group text__small">
                                                        <li class="col-md-1 d-none d-lg-block"><img src="/img/activity/<?=$withdrawParams['img'];?>" alt=""></li>
                                                        <li class="col-md-3 w5 d-none d-lg-block"><?=$type['title']?></li>
                                                        <li class="col-md-3 d-none d-lg-block"><?
                                                            echo $withdraw['title'];
                                                            if($withdraw['type'] == 3){
                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }elseif($withdraw['type'] == 4){
                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }
                                                            ?></li>
                                                        <li class="col-md-1 d-none d-lg-block"><?=date("d.m.Y H:i", $withdraw['time'])?></li>
                                                        <li class="col-md-2 d-none d-lg-block text-end">
                                                            <div class="d-flex align-item-cente">
                                                                <div class="circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></div>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-2 w5 d-none d-lg-block green">
                                                            <?if(!empty($sum)):?>

                                                                <?if($type['minus'] == 1):?>
                                                                    <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?else:?>
                                                                    <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?endif;?>


                                                            <?endif;?>
                                                        </li>
                                                        <div class="col-md-12 d-md-block d-lg-none">
                                                            <div class="block__body-mobil">
                                                                <div class="d-flex align-item-center">
                                                                    <img src="/img/activity/buy.svg" alt="">
                                                                    <div class="text__group ml-3">
                                                                        <span class="text__small w7"><?=$type['title']?></span>
                                                                        <span class="text__small"><?
                                                                            echo $withdraw['title'];
                                                                            if($withdraw['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }elseif($withdraw['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }
                                                                            ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="text__group text-end">
                                                                <span class="green w5"><?if(!empty($sum)):?>

                                                                        <?if($type['minus'] == 1):?>
                                                                            <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?else:?>
                                                                            <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?endif;?>


                                                        <?endif;?></span>
                                                                    <span><?=date("d.m.Y H:i", $withdraw['time'])?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?}?>
                                <?}?>
                            </div>
                        </div>
                        <div class="tab-pane fade <?=($tab==4)?'show active':''?>" id="transfers" role="tabpanel" aria-labelledby="transfers-tab">
                            <div class="col-lg-12">

                                <?
                                $i = 0;
                                foreach ($actions as $withdraw) {
                                    if($withdraw['type'] == 3 ){
                                        $i++;
                                        $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                        $class = "any-notificate";
                                        $curw = "CV";
                                        $sum = $withdraw['sum'];
                                        if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
                                            $curw = "GRC";
                                            $sum = $withdraw['tokens'];
                                        }
                                        $withdrawParams = getParams($withdraw, $class);
                                        ?>
                                        <div class="text-left">
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="row list__group text__small">
                                                        <li class="col-md-1 d-none d-lg-block"><img src="/img/activity/<?=$withdrawParams['img'];?>" alt=""></li>
                                                        <li class="col-md-3 w5 d-none d-lg-block"><?=$type['title']?></li>
                                                        <li class="col-md-3 d-none d-lg-block"><?
                                                            echo $withdraw['title'];
                                                            if($withdraw['type'] == 3){
                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }elseif($withdraw['type'] == 4){
                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                            }
                                                            ?></li>
                                                        <li class="col-md-1 d-none d-lg-block"><?=date("d.m.Y H:i", $withdraw['time'])?></li>
                                                        <li class="col-md-2 d-none d-lg-block text-end">
                                                            <div class="d-flex align-item-cente">
                                                                <div class="circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></div>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-2 w5 d-none d-lg-block green">
                                                            <?if(!empty($sum)):?>

                                                                <?if($type['minus'] == 1):?>
                                                                    <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?else:?>
                                                                    <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                <?endif;?>


                                                            <?endif;?>
                                                        </li>
                                                        <div class="col-md-12 d-md-block d-lg-none">
                                                            <div class="block__body-mobil">
                                                                <div class="d-flex align-item-center">
                                                                    <img src="/img/activity/buy.svg" alt="">
                                                                    <div class="text__group ml-3">
                                                                        <span class="text__small w7"><?=$type['title']?></span>
                                                                        <span class="text__small"><?
                                                                            echo $withdraw['title'];
                                                                            if($withdraw['type'] == 3){
                                                                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }elseif($withdraw['type'] == 4){
                                                                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                                                            }
                                                                            ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="text__group text-end">
                                                                <span class="green w5">
                                                                    <?if(!empty($sum)):?>

                                                                        <?if($type['minus'] == 1):?>
                                                                            <span>- <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?else:?>
                                                                            <span> <?=$sum?> <span class=""><?=$curw?></span></span>
                                                                        <?endif;?>


                                                                    <?endif;?></span>
                                                                    <span><?=date("d.m.Y H:i", $withdraw['time'])?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?}?>
                                <?}?>


                            </div>
                        </div>
                        <div class="tab-pane fade <?=($tab==5)?'show active':''?>" id="withdraws" role="tabpanel" aria-labelledby="withdraws-tab">
                            <div class="col-lg-12">
                                <?
                                $i = 0;
                                foreach ($withdraws as $withdraw) {
                                    $i++;
                                    $class = "transaction-notificate output";
                                    //$type = \common\models\ActionTypes::findOne($withdraw['type']);
                                    $withdrawParams = getParams($withdraw, $class);
                                    $curw = "PV"; // вывод только через pv
                                    $sum = $withdraw['sum'];
//                                    if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
//                                        $curw = "GRC";
//                                        $sum = $withdraw['tokens'];
//                                    }
                                    ?>

                                    <div class="text-left">
                                        <div class="row">
                                            <div class="col-12">
                                                <ul class="row list__group text__small">
                                                    <li class="col-md-1 d-none d-lg-block"><img src="/img/activity/<?=$withdrawParams['img'];?>" alt=""></li>
                                                    <li class="col-md-3 w5 d-none d-lg-block">

                                                    <span>Счет:</span><span><?=$withdraw['account']?></span>
                                                    <span>Комиссия:</span><span><?=$withdraw['fee']?></span>
                                                    <span>На вывод:</span><span><?=$withdraw['sum2']?></span>

                                                    </li>
                                                    <li class="col-md-3 d-none d-lg-block">
                                                        <span>Система:</span><span>
                                                            <?
                                                            if($withdraw['system_id'] == 1){
                                                                echo "Advcash";
                                                            }elseif($withdraw['system_id'] == 2){
                                                                echo "Perfect Money";
                                                            }elseif($withdraw['system_id'] == 3){
                                                                echo "Payeer";
                                                            } ?>
                                                        </span>
                                                    </li>
                                                    <li class="col-md-1 d-none d-lg-block"><?=date("d.m.Y H:i", $withdraw['time'])?></li>
                                                    <li class="col-md-2 d-none d-lg-block text-end">
                                                        <div class="d-flex align-item-cente">
                                                            <div class="circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></div>
                                                        </div>
                                                    </li>
                                                    <li class="col-md-2 w5 d-none d-lg-block green">
                                                        <?if(!empty($sum)):?>


                                                                <span>- <?=$sum?> <span class=""><?=$curw?></span></span>



                                                        <?endif;?>
                                                    </li>
                                                    <li><?
                                                        if($withdraw['status'] == 3){?>
                                                            <a class="btn btn-primary" href="" data-toggle="modal"  data-target="#cancel<?=$withdraw['id']?>Modal">Отменить</a>
                                                            <div class="modal fade" id="cancel<?=$withdraw['id']?>Modal" tabindex="-1" role="dialog" aria-labelledby="cance<?=$withdraw['id']?>ModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog ">
                                                                    <div class="modal-content">
                                                                        <div class="modal-body">
                                                                            <p>Вы действительно хотите отменить вывод?</p>
                                                                            <p>
                                                                                <a href="/profile/withdrawcancel?id=<?=$withdraw['id']?>" class="btn btn-success">Да </a>
                                                                                <button class="btn btn-danger" data-dismiss="modal">Нет </button>
                                                                            </p>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?}?>
                                                    </li>
                                                    <div class="col-md-12 d-md-block d-lg-none">
                                                        <div class="block__body-mobil">
                                                            <div class="d-flex align-item-center">
                                                                <img src="/img/activity/buy.svg" alt="">
                                                                <div class="text__group ml-3">
                                                                    <span class="text__small w7">
                                                                        <span>Счет:</span><span><?=$withdraw['account']?></span>
                                                                        <span>Комиссия:</span><span><?=$withdraw['fee']?></span>
                                                                        <span>На вывод:</span><span><?=$withdraw['sum2']?></span>
                                                                    </span>
                                                                    <span class="text__small">
                                                                        <?
                                                                        if($withdraw['system_id'] == 1){
                                                                            echo "Advcash";
                                                                        }elseif($withdraw['system_id'] == 2){
                                                                            echo "Perfect Money";
                                                                        }elseif($withdraw['system_id'] == 3){
                                                                            echo "Payeer";
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="text__group text-end">
                                                                <span class="green w5">
                                                                    <?if(!empty($sum)):?>


                                                                            <span>- <?=$sum?> <span class=""><?=$curw?></span></span>



                                                                    <?endif;?></span>
                                                                <span><?=date("d.m.Y H:i", $withdraw['time'])?></span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <??>
                                <?}?>
                            </div>
                        </div>

                    </div>
                    <br>
                    <br>
                    <?
                    echo LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                    ?>


                </div>

            </div>


        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>