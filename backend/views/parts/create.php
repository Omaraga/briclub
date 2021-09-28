<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Parts */
$c_id = Yii::$app->request->get()['c_id'];
$course = \common\models\Courses::findOne($c_id);
$this->title = 'Добавить раздел';
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="parts-create">

    <?= $this->render('_form', [
        'model' => $model,
        'c_id'=>$c_id
    ]) ?>

</div>
