<?php

use common\models\CourseScreens;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Parts */
$c_id = $model->course_id;
/* @var $this yii\web\View */
/* @var $model common\models\CourseScreens */
$this->title = 'Изменить раздел: ' . $model->title;
$course = \common\models\Courses::findOne($c_id);
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];

$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="parts-update">
    <?= $this->render('_form', [
        'model' => $model,
        'c_id'=>$c_id
    ]) ?>

</div>
