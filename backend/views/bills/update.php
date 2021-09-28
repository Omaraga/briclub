<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Bills */
/* @var $users common\models\User */

$this->title = 'Редактировать счет №' . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Bills', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bills-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users
    ]) ?>

</div>
