<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Bills */
/* @var $users common\models\User */


$this->title = 'Выставить счет';
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bills-create">

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
    ]) ?>

</div>
