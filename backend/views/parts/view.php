<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Parts */

$this->title = $model->title;
$course = \common\models\Courses::findOne($model->course_id);
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = ['label' => 'Модули'];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$lessons = \common\models\Lessons::find()->where(['part_id'=>$model->id])->orderBy('position asc');
?>
<div class="parts-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'label'=>'Курс',
                'value'=>function($data){
                    return \common\models\Courses::findOne($data['course_id'])['title'];
                }
            ],
            [
                'label'=>'Бесплатно',
                'value'=>function($data){
                    if($data['free']== 1){return "Да";}else{return "Нет";};
                }
            ],
            [
                'label'=>'Водный курс',
                'value'=>function($data){
                    if($data['is_intro']== 1){return "Да";}else{return "Нет";};
                }
            ],
        ],
    ]) ?>

</div>

<div class="parts-index">

    <h3><?= Html::encode('Уроки') ?></h3>

    <p>
        <?= Html::a('Добавить урок', ['/lessons/create?part_id='.$model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все уроки модуля "<?=$model->title?>"</h3>



        </div>
        <!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
                <tr>
                    <th>Позиция</th>
                    <th>Сдвинуть</th>
                    <th>Название модуля</th>
                </tr>

                <?

                $sc_count = count($lessons->all());
                $i = 0;
                foreach ($lessons->all() as $part) {
                    $i++;

                    ?>

                    <tr>
                        <td><?=$part['position']?></td>
                        <td >
                            <? if($part['position']>1){?>
                                <a style="margin-right: 20px" href="/lessons/position-up?id=<?=$part['id']?>"><i class="fa fa-arrow-up"></i></a>
                                <?
                                $style = '';
                            }else{
                                $style = 'style="margin-left: 37px"';
                            } ?>

                            <? if($i != $sc_count){?>
                                <a <?=$style?> href="/lessons/position-down?id=<?=$part['id']?>"><i class="fa fa-arrow-down"></i></a>
                            <?} ?>
                        </td>
                        <td><a href="/lessons/view?id=<?=$part['id']?>">Урок: <?=$part['title']?></a></td>

                    </tr>

                <?}
                ?>
                </tbody></table>
        </div>
        <!-- /.box-body -->
    </div>
</div>
