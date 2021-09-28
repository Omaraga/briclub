<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$main_url = Yii::$app->params['mainUrl'];

/* @var $this yii\web\View */
/* @var $model common\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <div class="form-group">
    <?
    if($model->type == 5){
        echo $form->field($model, 'link')->dropDownList($screens_array);
    }elseif($screen == 2 or $screen == 3 or $screen == 12 or $screen == 17){
        if($model->type == 9 or $model->type == 11 or $model->type == 25 or $model->type == 64 or $model->type == 66 ){
            $images = \common\models\Images::find()->all();
            $vars = array();
            foreach ($images as $image) {
                $screens = explode('-',$image['screens']);
                foreach ($screens as $scree) {
                    if($scree == $screen){
                        $vars[$image['link']] = $image['link'];
                    }
                }
            }
            if(!empty($model->link)){
                echo "Текущее изображение";
                echo "<br>";
                echo "<img src='$main_url/$model->link' width='150px'>";
            }
           echo $form->field($model, 'file')->fileInput(['class'=>'btn btn-primary']);
        }elseif ($model->type == 68 or $model->type == 69){
            echo $form->field($model, 'link')->dropDownList($screens_array);
        }
    }elseif ($screen == 7 or $screen == 10 or $screen == 14 or $screen == 15){
        if($model->type == 31 or $model->type == 40 or $model->type == 57 or $model->type == 61){
            if(!empty($model->link))
            {
                echo Html::img($main_url.'/'.$model->link, ['class'=>'img-responsive','width'=>'200px']);
            }
            echo $form->field($model, 'file')->fileInput(['class'=>'btn btn-primary']);
        }
    }
     ?>
    </div>
    <div class="clearfix"></div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
