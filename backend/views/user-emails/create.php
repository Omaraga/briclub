<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserEmails */

$this->title = 'Create User Emails';
$this->params['breadcrumbs'][] = ['label' => 'User Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-emails-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
