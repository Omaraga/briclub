<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Lessons */

$c_id = $model->course_id;
$part_id = $model->part_id;
$course = \common\models\Courses::findOne($c_id);
$this->title = 'Изменить урок '. $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="lessons-update">

    <?= $this->render('_form', [
        'model' => $model,
        'part_id'=>$part_id,
        'c_id'=>$c_id
    ]) ?>

</div>
