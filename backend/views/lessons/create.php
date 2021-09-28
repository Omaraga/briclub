<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Lessons */

$part_id = Yii::$app->request->get()['part_id'];
$part = \common\models\Parts::findOne($part_id);
$course = \common\models\Courses::findOne($part['course_id']);

$this->title = 'Добавить урок';
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = ['label' => $part['title'], 'url' => ['/parts/view?id='.$part['id']]];
$this->params['breadcrumbs'][] = 'Добавить урок';
?>
<div class="lessons-create">

    <?= $this->render('_form', [
        'model' => $model,
        'part_id'=>$part_id,
        'c_id'=>$course['id'],
    ]) ?>

</div>
