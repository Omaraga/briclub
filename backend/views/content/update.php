<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Content */
$this->title = \common\models\ContentTypes::findOne($model->type)['title'];
$course = \common\models\Courses::findOne($course_id);
$screen1 = \common\models\CourseScreens::findOne($model->screen_course_id);
$screens = \common\models\CourseScreens::find()->where(['course_id'=>$course_id])->all();
foreach ($screens as $screen) {
    $screens_array2['/'] = 'Главная страница';
    $screens_array2["#s-".$screen['id']] = $screen['title'];
}
/*echo "<pre>";
var_dump($screens_array);
echo "</pre>";
exit;*/
$screens_array = [
    'Экраны сайта' => $screens_array2,
    'Модальные окна' => [
        'm1' => 'Заявки',
        'm2' => 'Запросы на консультацию',
        'm3' => 'Вопросы',
        'm4' => 'Отзывы',
    ]
];
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = ['label' => $screen1['title'], 'url' => ['course-screens/view?id='.$screen1['id']]];
$this->params['breadcrumbs'][] = ['label' => $this->title];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="content-update">

    <?= $this->render('_form', [
        'model' => $model,
        'screens_array' => $screens_array,
        'screen' => $screen1['screen_id']
    ]) ?>

</div>
