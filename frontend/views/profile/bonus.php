<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Бонусная программа Global";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$promotion = \common\models\Promotion::find()->where(['status'=>1])->one();
$children = \common\models\User::find()->where(['parent_id'=>$user['id']])->orderBy('order asc')->all();
$part1 = array();
$part2 = array();
$i = 0;
$all_children1 = array();
$all_children2 = array();
foreach ($children as $child) {
    $i++;
    if($i%2 == 1){
        $part1[] = $child['id'];
        $all_children1 = array_merge($all_children1,\common\models\Parents::find()->where(['parent_id'=>$child['id']])->andWhere(['>=','time',$promotion->start])->andWhere(['<','time',$promotion->end])->all());
    }else{
        $part2[] = $child['id'];
        $all_children2 = array_merge($all_children2,\common\models\Parents::find()->where(['parent_id'=>$child['id']])->andWhere(['>=','time',$promotion->start])->andWhere(['<','time',$promotion->end])->all());
    }

}

$own_children = \common\models\Parents::find()->where(['parent_id'=>$user['id'],'level'=>1])->andWhere(['>=','time',$promotion->start])->andWhere(['<','time',$promotion->end])->all();
$k = 0;
foreach ($own_children as $child2) {
    $k++;
    if($k%2 == 1){
        $all_children1[] = $child2;
    }else{
        $all_children2[] = $child2;
    }

}


$level = "Нет уровня";
$prom = \common\models\Promotion::find()->where(['status'=>1])->one();
$profit = 0;

$user_pr = \common\models\UserPromotions::find()->where(['user_id'=>$user['id'],'pr_id'=>$prom['id']])->orderBy('id desc')->one();
if(!empty($user_pr)){
    $level = \common\models\BonusTarifs::findOne($user_pr['tarif_id'])['title'];
    $profit = \common\models\BonusTarifs::findOne($user_pr['tarif_id'])['sum'];
}
 ?>

    <main class="cours">
        <div class="container">
            <div class="row">
            <?/*=\frontend\components\NavWidget::widget()*/?>

                <main role="main" class="structure">
                    <div class="hgroup">
                        <h1 class="h1">Еженедельная бонусная программа Global</h1>
                    </div>
                    <h5 class="h5">Начало недели: <?=date('d.m.Y H:i',$promotion['start'])?></h5>
                    <h5 class="h5">Конец недели: <?=date('d.m.Y H:i',$promotion['end'])?></h5>
                    <h5 class="h5">Ваш уровень: <?=$level?></h5>
                    <h5 class="h5">Текущий заработок: <?=$profit?></h5>
                    <div class="structure-wrap">
                        <div class="row">
                            <div class="col-lg-6">
                                <p>Левое плечо</p>
                                <p>Всего в структуре: <?=count($all_children1)?> партнеров</p>
                                <? if(!empty($all_children1)){
                                    foreach ($all_children1 as $item) {?>
                                        <div class="avatar-block mb-3">
                                            <div class="avatar-icon">
                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                            </div>

                                            <div class="avatar-block-wrap">
                                                <h4 class="h4 mb-0"><?=\common\models\User::findOne($item['user_id'])['username']?></h4>
                                            </div>
                                        </div>
                                    <?}
                                } ?>
                            </div>
                            <div class="col-lg-6">
                                <p>Правое плечо</p>
                                <p>Всего в структуре: <?=count($all_children2)?> партнеров</p>
                                <? if(!empty($all_children2)){
                                    foreach ($all_children2 as $item2) {?>
                                        <div class="avatar-block mb-3">
                                            <div class="avatar-icon">
                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                            </div>

                                            <div class="avatar-block-wrap">
                                                <h4 class="h4 mb-0"><?=\common\models\User::findOne($item2['user_id'])['username']?></h4>
                                            </div>
                                        </div>
                                    <?}
                                } ?>
                            </div>

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
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>