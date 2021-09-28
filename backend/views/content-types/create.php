<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContentTypes */

$this->title = 'Create Content Types';
$this->params['breadcrumbs'][] = ['label' => 'Content Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
