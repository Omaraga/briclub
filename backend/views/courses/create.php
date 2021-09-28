<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Courses */

$this->title = 'Добавить новый курс';
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
