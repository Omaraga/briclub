<?php

use common\models\User;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заработок пользователей';
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = $this->title;
$users = User::find()->where(['activ'=>1])->all();
?>

<div class="user-index">
    <?
        $actions1 = \common\models\Actions::find()->where(['type'=>7,'sum'=>100])->count();
    ?>
    <p>На столе 1:<br><br>  <b><?=$actions1?></b> пользователей получили выплаты, в сумме заработано <b>$<?=($actions1*100)?></b></p>
    <br>
    <hr>
    <?
    $actions3 = \common\models\Actions::find()->where(['type'=>7,'sum'=>700])->count();
    $kuractions3 = \common\models\Actions::find()->where(['type'=>105,'sum'=>200])->count();
    ?>

    <p>На столе 3:<br> <br> <b><?=$actions3?></b> пользователей получили выплаты, на сумму <b>$<?=($actions3*700)?></b></p>
    <p><b><?=$kuractions3?></b> пользователей получили кураторские бонусы, на сумму <b>$<?=($kuractions3*200)?></b></p>
    <p>Всего заработано на столе <b>$<?=($actions3*700)+($kuractions3*200)?></b></p>
    <br>
    <hr>
    <?
    $actions4 = \common\models\Actions::find()->where(['type'=>7,'sum'=>1500])->count();
    $kuractions4 = \common\models\Actions::find()->where(['type'=>105,'sum'=>400])->count();
    ?>
    <p>На столе 4: <br><br> <b><?=$actions4?></b> пользователей получили выплаты, на сумму <b>$<?=($actions4*1500)?></b></p>
    <p><b><?=$kuractions4?></b> пользователей получили кураторские бонусы, на сумму <b>$<?=($kuractions4*400)?></b></p>
    <p>Всего заработано на столе <b>$<?=($actions4*1500)+($kuractions4*400)?></b></p>
    <br>
    <hr>



    <?
    $actions5 = \common\models\Actions::find()->where(['type'=>7,'sum'=>5000])->count();
    $kuractions5 = \common\models\Actions::find()->where(['type'=>105,'sum'=>600])->count();
    ?>
    <p>На столе 5: <br><br> <b><?=$actions5?></b> пользователей получили выплаты, на сумму <b>$<?=($actions5*5000)?></b></p>
    <p><b><?=$kuractions5?></b> пользователей получили кураторские бонусы, на сумму <b>$<?=($kuractions5*600)?></b></p>
    <p>Всего заработано на столе <b>$<?=($actions5*5000)+($kuractions5*600)?></b></p>
    <br>
    <hr>

    <?
    $actions6 = \common\models\Actions::find()->where(['type'=>7,'sum'=>13000])->count();
    $kuractions6 = \common\models\Actions::find()->where(['type'=>105,'sum'=>3000])->count();
    ?>
    <p>На столе 6: <br><br> <b><?=$actions6?></b> пользователей получили выплаты, на сумму <b>$<?=($actions6*13000)?></b></p>
    <p><b><?=$kuractions6?></b> пользователей получили кураторские бонусы, на сумму <b>$<?=($kuractions6*3000)?></b></p>
    <p>Всего заработано на столе <b>$<?=($actions6*13000)+($kuractions6*3000)?></b></p>
    <br>
    <hr>

</div>
