<?php

use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заработок компании со столов';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
$users = User::find()->where(['activ'=>1])->all();
?>

<div class="user-index">
    <?
    $all_res = 0;
    foreach ($users as $user) {?>
        <p class="h4"><?=$user['username']?></p>
        <p>

            Стол 1:
            <br>
            <?

            $all = 0;
            $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>1,'reinvest'=>0])->all();
            if(!empty($mats)){
                foreach ($mats as $mat) {
                    echo "Заработок за место [".$mat['id']."]"." $3<br>";
                    $all +=3;
                }
            }
            echo "Общий заработок с 1 стола: $".$all;
            ?>
        </p>
        <p>
            Стол 4:
            <br>
            <?

            $all4 = 0;
            $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>4,'slots'=>1])->all();
            if(!empty($mats)){
                foreach ($mats as $mat) {
                    echo "Заработок за место [".$mat['id']."]"." $100<br>";
                    $all4 +=100;
                }
            }
            echo "Общий заработок с 4 стола: $".$all4;
            ?>
        </p>
        <p>
            Стол 5:
            <br>
            <?

            $all5 = 0;
            $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>5,'slots'=>1])->all();
            if(!empty($mats)){
                foreach ($mats as $mat) {
                    echo "Заработок за место [".$mat['id']."]"." $400<br>";
                    $all5 +=400;
                }
            }
            echo "Общий заработок с 5 стола: $".$all5;
            ?>
        </p>
        <p>
            Стол 6:
            <br>
            <?

            $all6 = 0;
            $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>6,'slots'=>1])->all();
            if(!empty($mats)){
                foreach ($mats as $mat) {
                    echo "Заработок за место [".$mat['id']."]"." $800<br>";
                    $all6 +=800;
                }
            }
            $mats2 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>6,'slots'=>3])->all();
            if(!empty($mats2)){
                foreach ($mats2 as $mat2) {
                    echo "Заработок за место [".$mat2['id']."]"." $16200<br>";
                    $all6 +=16200;
                }
            }
            $mats3 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>6,'slots'=>4])->all();
            if(!empty($mats3)){
                foreach ($mats3 as $mat3) {
                    echo "Заработок за место [".$mat3['id']."]"." $16200<br>";
                    $all6 +=16200;
                }
            }
            $res = $all+$all4+$all5+$all6;
            echo "Общий заработок с 6 стола: $".$all6;
            echo "<br><br>Общий заработок со всех столов: $".$res."<br><hr>";
            ?>
        </p>

        <?
        $all_res+= $res;
    }
    ?>
    <h3>Всего компания заработала со столов пользователей $<?=$all_res?></h3>
</div>
