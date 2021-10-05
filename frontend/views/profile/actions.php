<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;
use yii\data\Pagination;
//use yii\widgets\LinkPager;
use yii\bootstrap4\LinkPager;

$this->title = "Активность";
//$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css');


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
            <h4 class="w7 mb-4">Активность</h4>

            <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Все</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-system-tab" data-toggle="pill" href="#pills-system" role="tab" aria-controls="pills-system" aria-selected="false">Система</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-action-tab" data-toggle="pill" href="#pills-action" role="tab" aria-controls="pills-action" aria-selected="false">Акции</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-transfers-tab" data-toggle="pill" href="#pills-transfers" role="tab" aria-controls="pills-transfers" aria-selected="false">Поступления</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-withdraws-tab" data-toggle="pill" href="#pills-withdraws" role="tab" aria-controls="pills-withdraws" aria-selected="false">Выводы</a>
                </li>
            </ul>
            <div class="tab-content mt-1 block__body" id="pills-tabContent">
                <div class="tab-pane fade <?=($tab==0)?'show active':''?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
                    <div class="activity-line between">
                        <div class="flex-line">
                            <img src="/img/payment/arrow-green.svg" alt="">
                            <div class="rows ml-3">
                            <ul class="row list__group text__small">
                                <p class="txt-mini"><?=$type['title']?></p>
                                <p class="txt-6A7 hiden">Баланс PV</p>
                            </div>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini hiden2"><?
                            echo $withdraw['title'];
                            if($withdraw['type'] == 3){
                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }elseif($withdraw['type'] == 4){
                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }
                            ?></p>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini hiden2"><?=date("d.m.Y H:i", $withdraw['time'])?></p>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini mr-4 hiden2"><img src="/img/payment/circle-<?=$withdrawParams['color'];?>.svg"><?=$withdrawParams['text'];?></p>
                            <div class="text-right">
                                <h6 class="txt-green-100">CV <span>
                                <?if(!empty($sum)):?>
                                    <?=$sum?>
                                <?endif;?>
                                </span></h6>
                                <p class="txt-6A7 hiden"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></span></p>
                            </div>
                        </div>
                    </div>
                    <?}?>
                </div>
                <div class="tab-pane fade <?=($tab==1)?'show active':''?>" id="pills-system" role="tabpanel" aria-labelledby="system-tab">
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
                    <div class="activity-line between">
                        <div class="flex-line">
                            <img src="/img/payment/arrow-green.svg" alt="">
                            <div class="rows ml-3">
                                <p class="txt-mini"><?=$type['title']?></p>
                                <p class="txt-6A7 hiden">Баланс PV</p>
                            </div>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini hiden2"><?
                                echo $withdraw['title'];
                                if($withdraw['type'] == 3){
                                    echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                }elseif($withdraw['type'] == 4){
                                    echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                }
                                ?>
                            </p>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini hiden2"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></span></p>
                        </div>
                        <div class="flex-line align-items-center">
                            <p class="txt-mini mr-4 hiden2 circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></p>
                            <div class="text-right">
                                <h6 class="txt-green-100">CV <span > <?if(!empty($sum)):?><?=$sum?><?endif;?></span></h6>
                                <p class="txt-6A7 hiden"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></span></p>
                            </div>
                        </div>
                    </div>
                    <?}?>
                    <?}?>



                        </div>
                <div class="tab-pane fade <?=($tab==2)?'show active':''?>" id="pills-action" role="tabpanel" aria-labelledby="action-tab">

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

                    <div class="activity-line between">
                        <div class="flex-line">
                            <img src="/img/payment/arrow-red.svg" alt="">
                            <div class="rows ml-3">
                                <p class="txt-mini"><?=$type['title']?></p>
                                <p class="txt-6A7 hiden">Баланс PV</p>
                            </div>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini hiden2"><?
                            echo $withdraw['title'];
                            if($withdraw['type'] == 3){
                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }elseif($withdraw['type'] == 4){
                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }
                                ?></p>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini hiden2"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></span></p>
                        </div>
                        <div class="flex-line align-items-center">
                            <p class="txt-mini mr-4 hiden2 circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></p>
                            <div class="text-right">
                                <h6 class="txt-green-100">CV <span ><?if(!empty($sum)):?><?=$sum?><?endif;?></span></h6>
                                <p class="txt-6A7 hiden"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></p>
                            </div>
                        </div>
                    </div>


                                    <?}?>
                                <?}?>



                        </div>
                <div class="tab-pane fade <?=($tab==3)?'show active':''?>" id="pills-transfers" role="tabpanel" aria-labelledby="transfers-tab">
                    <?
                    $i = 0;
                    foreach ($actions as $withdraw) {
                        if($withdraw['type'] == 4 or $withdraw['type'] == 5 or $withdraw['type'] == 6){
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
                    <div class="activity-line between">
                        <div class="flex-line">
                            <img src="/img/payment/arrow-green.svg" alt="">
                            <div class="rows ml-3">
                                <p class="txt-mini"><?=$type['title']?></p>
                                <p class="txt-6A7 hiden">Баланс PV</p>
                            </div>
                        </div>
                        <div class="flex-line">
                            <? echo $withdraw['title'];
                            if($withdraw['type'] == 3){
                                echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }elseif($withdraw['type'] == 4){
                                echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                            }?>
                        </div>
                        <div class="flex-line">
                            <p class="txt-mini hiden2"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></span></p>
                        </div>
                        <div class="flex-line align-items-center">
                            <p class="txt-mini mr-4 hiden2 circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></p>
                            <div class="text-right">
                                <h6 class="txt-green-100">CV <span ><?if(!empty($sum)):?>
                                <?=$sum?>
                                <?endif;?>
                                </span></h6>
                                <p class="txt-6A7 hiden"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></span></p>
                            </div>
                        </div>
                    </div>
                    <?}?>
                    <?}?>
                </div>
                <div class="tab-pane fade <?=($tab==4)?'show active':''?>" id="pills-withdraws" role="tabpanel" aria-labelledby="withdraws-tab">
                <?
                    $i = 0;
                    foreach ($withdraws as $withdraw) {
                    $i++;
                    $class = "transaction-notificate output";
                    //$type = \common\models\ActionTypes::findOne($withdraw['type']);
                    $withdrawParams = getParams($withdraw, $class);
                    $curw = "PV"; // вывод только через pv
                    $sum = $withdraw['sum'];
//                  if($withdraw['type'] == 55 or $withdraw['type'] == 56 or $withdraw['type'] == 57 or $withdraw['type'] == 58 or $withdraw['type'] == 59 or $withdraw['type'] == 60 or $withdraw['type'] == 61 or $withdraw['type'] == 62 or $withdraw['type'] == 63){
//                  $curw = "GRC";
//                  $sum = $withdraw['tokens'];
//                  }
                    ?>
                    <div class="activity-line between">
                    <div class="flex-line">
                        <img src="/img/payment/arrow-red.svg" alt="">
                        <div class="rows ml-3">
                            <span>Счет:</span><span><?=$withdraw['account']?></span>
                            <span>Комиссия:</span><span><?=$withdraw['fee']?></span>
                            <span>На вывод:</span><span><?=$withdraw['sum2']?></span>
                        </div>
                    </div>
                    <div class="flex-line">
                        <span>Система:</span>
                        <span>
                            <?
                            if($withdraw['system_id'] == 1){
                                echo "Advcash";
                            }elseif($withdraw['system_id'] == 2){
                                echo "Perfect Money";
                            }elseif($withdraw['system_id'] == 3){
                                echo "Payeer";
                            } ?>
                        </span>
                    </div>
                    <div class="flex-line">
                        <p class="txt-mini hiden2"><?=date("d.m.Y", $withdraw['time'])?><span class="ml-3"><?=date("H:i", $withdraw['time'])?></span></p>
                    </div>
                    <div class="flex-line align-items-center">
                        <p class="txt-mini mr-4 hiden2 circle__<?=$withdrawParams['color'];?>"><?=$withdrawParams['text'];?></p>
                        <div class="text-right">
                            <h6 class="txt-green-100">CV <span > <?if(!empty($sum)):?><?=$sum?><?endif;?></span></h6>
                            <p class="txt-6A7 hiden">27.09.2021 <span>17:45</span></p>
                        </div>
                    </div>
                    <div class="flex-line">
                        <?
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
                    </div>
                </div>


                                <?}?>
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


    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>