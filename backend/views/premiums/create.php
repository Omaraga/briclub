<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Premiums */

$this->title = 'Добавить премиум-аккаунт';
$this->params['breadcrumbs'][] = ['label' => 'Премиум-аккаунты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="premiums-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
