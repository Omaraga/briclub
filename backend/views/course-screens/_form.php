<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$course_id = Yii::$app->request->get()['c_id'];
$screens = \common\models\Screens::find()->all();
$screens_array = array();
foreach ($screens as $screen) {
    if($screen['id'] == 1 or $screen['id'] == 2 or $screen['id'] == 4 or $screen['id'] == 6 or $screen['id'] == 7 or $screen['id'] == 8 or $screen['id'] == 11){
        $screens_array[$screen['id']] = $screen['title'];
    }
}
/* @var $this yii\web\View */
/* @var $model common\models\CourseScreens */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-screens-form">

    <?php $form = ActiveForm::begin(); ?>

    <?
        echo $form->field($model, 'screen_id')->dropDownList($screens_array);
    ?>

    <?= $form->field($model, 'course_id')->hiddenInput(['value'=>$course_id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
