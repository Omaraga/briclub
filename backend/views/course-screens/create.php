<?php

use yii\helpers\Html;
$c_id = Yii::$app->request->get()['c_id'];
/* @var $this yii\web\View */
/* @var $model common\models\CourseScreens */

$this->title = 'Добавить экран';
$course = \common\models\Courses::findOne($c_id);
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-screens-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
