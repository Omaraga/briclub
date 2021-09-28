<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DepositAccounts */

$this->title = 'Изменить счет: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Счета для пополнения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deposit-accounts-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
