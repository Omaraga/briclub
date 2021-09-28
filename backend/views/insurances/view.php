<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Insurances */

$this->title = "Заяка на страхование " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Insurances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="insurances-view">

    <p>Передняя сторона</p>
    <?if($model->img != null){?>
        <a href="https://test.shanyrakplus.com<?=$model->img?>">
            <img src="https://test.shanyrakplus.com<?=$model->img?>" width="500px" alt="">
            <p>Открыть картинку в новой вкладке</p>
        </a>

    <?}?>
    <br><br>
    <p>Задняя сторона</p>
    <?if($model->img2 != null){?>
        <a href="https://test.shanyrakplus.com<?=$model->img2?>">
            <img src="https://test.shanyrakplus.com<?=$model->img2?>" width="500px" alt="">
            <p>Открыть картинку в новой вкладке</p>
        </a>

    <?}?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'address',
            'phone',
            'email:email',
            [
                'label' => 'Пользователь',
                'value' => function($data){
                    return \common\models\User::findOne($data['user_id'])->username;
                }
            ],
            [
                'label' => 'Статус',
                'value' => function($data){
                    $status = "";
                    if($data['status'] == 1){
                        $status = "Принято";
                    }
                    else if($data['status'] == 2){
                        $status = "На рассмотрении";
                    }
                    else if($data['status'] == 3){
                        $status = "Отклонено";
                    }
                    return $status;
                }
            ],
            [
                'label'=>'Дата',
                'value'=>function($data){
                    return date("d.m.Y H:i",$data['created_at']);
                }
            ]
        ],
    ]) ?>

</div>
