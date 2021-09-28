<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */
$main_url = Yii::$app->params['mainUrl'];
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="actions-view">
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
            'title',
            'text',
            'start_date',
            'end_date',
            'time:datetime',
            'status',
        ],
    ]) ?>
    <?
    if(!empty($model->link)){?>
        <img src="<?=$main_url?><?=$model->link?>" class="img" width="300" alt="">
    <?}
    ?>
    <h3 class="box-title">Билеты на мероприятие "<?=$model->title?>"</h3>
    <p>
        <?= Html::a('Добавить билет', ['/event-ticket-types/create?event_id='.$model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box">
        <!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
                <tr>
                    <th>Название</th>
                    <th>Всего билетов</th>
                    <th>Осталось билетов</th>
                    <th>Цена</th>
                    <th>Изображение</th>
                    <th>Изменить</th>
                </tr>

                <?
                $docs = \common\models\EventTicketTypes::find()->where(['event_id'=>$model->id])->all();
                $i = 0;
                foreach ($docs as $doc) {
                    $i++;
                    $link = $doc['link'];
                    ?>

                    <tr>
                        <td><?=$doc['title']?></td>
                        <td><?=$doc['count']?></td>
                        <td><?=$doc['count']?></td>
                        <td><?=$doc['price']?></td>
                        <td><img src="<?=$link?>" class="img" width="150" alt=""></td>

                        <td>
                            <?= Html::a('Изменить', ['/event-ticket-types/update', 'id' => $doc['id']], [
                                'class' => 'btn btn-link',
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
</div>
