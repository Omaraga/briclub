<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DepositAccounts */

$this->title = 'Create Deposit Accounts';
$this->params['breadcrumbs'][] = ['label' => 'Deposit Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-accounts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
