<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Screens */

$this->title = 'Create Screens';
$this->params['breadcrumbs'][] = ['label' => 'Screens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="screens-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
