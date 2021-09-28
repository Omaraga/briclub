<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LessonDocs */

$this->title = 'Добавить материал';
$this->params['breadcrumbs'][] = ['label' => 'Материалы урока', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-docs-create">

    <?= $this->render('_form', [
        'model' => $model,
        'lesson_id' => $lesson_id,
    ]) ?>

</div>
