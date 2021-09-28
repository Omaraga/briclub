<?php

use frontend\models\forms\HomeworkForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Lessons */

$this->title = $model->title;
$course = \common\models\Courses::findOne($model->course_id);
$part = \common\models\Parts::findOne($model->part_id);
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = ['label' => $part['title'], 'url' => ['/parts/view?id='.$part['id']]];
$this->params['breadcrumbs'][] = ['label' => 'Уроки'];
$this->params['breadcrumbs'][] = $this->title;
$HomeworkForm = new HomeworkForm();
$messages = \common\models\UserHomeworks::find()->where(['lesson_id'=>$model->id])->all();
$this->registerJs('
    $(".answer").click(function(){
        username = $(this).data("user");
        $("#username").val(username);
    });
');
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
\yii\web\YiiAsset::register($this);
?>
<div class="lessons-view">

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
            'vimeo_link',
            'youtube_link',
            //'private',
            [
                'label'=>'Курс',
                'value'=>function($data){
                    return \common\models\Courses::findOne($data['course_id'])['title'];
                }
            ],
            [
                'label'=>'Модуль',
                'value'=>function($data){
                    return \common\models\Parts::findOne($data['part_id'])['title'];
                }
            ],
            [
                'label'=>'Бесплатно',
                'value'=>function($data){
                    if($data['free']== 1){return "Да";}else{return "Нет";};
                }
            ],
            [
                'label'=>'Просмотры',
                'value'=>function($data){
                    $user_lessons = \common\models\UserLessons::find()->where(['lesson_id'=>$data['id']])->count();
                    return $user_lessons;
                }
            ],
            [
                'label'=>'Иконка',
                'value'=>function($data){
                    return $data['icon'];
                }
            ],
        ],
    ]) ?>
    <h3 class="box-title">Дополнительные материалы "<?=$model->title?>"</h3>
    <p>
        <?= Html::a('Добавить материал', ['/lesson-docs/create?lesson_id='.$model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box">
        <!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
                <tr>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Дата загрузки</th>
                    <th>Удалить</th>
                </tr>

                <?
                $docs = \common\models\LessonDocs::find()->where(['lesson_id'=>$model->id])->all();
                $i = 0;
                foreach ($docs as $doc) {
                    $i++;
                    $link = $doc['link'];
                    if($doc['type'] == 3){
                        $link = $doc['link2'];
                    }
                    ?>

                    <tr>
                        <td>
                            <a href="https://gcfond.com<?=$link?>" target="_blank">
                                <?=$doc['title']?>
                            </a>

                        </td>
                        <td >
                            <?
                                echo \common\models\LessonDocTypes::findOne($doc['type'])['title'];
                            ?>
                        </td>
                        <td>
                            <?=date('d.m.Y H:i')?>
                        </td>
                        <td>
                            <?= Html::a('Удалить', ['/lesson-docs/delete', 'id' => $doc['id']], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Уверены что хотите удалить?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>

                <?}
                ?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <h3>Домашние задания</h3>

    <?
    foreach ($messages as $message) {
        $user = \common\models\User::findOne($message['user_id']);
        ?>
        <div class="card mt-4 mb-5">
            <div class="card-header" <?if($message['is_admin'] == 1){?>style="background-color: #d2ecff;" <?}?>>
                <span><?if($message['is_admin'] == 1){echo "Ответ аддминистратора пользователю ".$user['fio']." ".$user['username'];}else{echo $user['fio']." ".$user['username'];}?></span>
                <span style="float: right;"><?=date('d.m.Y H:i',$message['time'])?></span>
            </div>
            <div class="card-body">
                <p class="card-text"><?=$message['text']?></p>
                <?if(!empty($message['link'])){?>
                    <a href="<?=$message['link']?>" class="btn btn-link">Скачать файл</a>
                <?}?>
                <a class="btn answer btn-primary" data-user="<?=$user['username']?>">Ответить</a>
            </div>
        </div>
    <?}?>
    <div class="card mt-6">
        <div class="card-header">
            Ответить
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['options'=>['class'=>'form mb-5']]); ?>
            <?= $form->field($HomeworkForm, 'user_id')->textInput(['id'=>'username'])->label(false) ?>
            <?= $form->field($HomeworkForm, 'text')->textarea(['rows' => 8,'placeholder'=>'Введите текст'])->label(false) ?>

            <div class="form-group">
                <? echo $form->field($HomeworkForm, 'file')->fileInput(['class'=>'form-control-file btn btn-link pl-0']);  ?>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-lg mt-3']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
