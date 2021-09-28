<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Documents */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="documents-view">

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
            'alias',
            'type',
            'title',
            'link',
            'status',
        ],
    ]) ?>
    <?
        $audios = \common\models\Audios::find()->where(['lib_id' => $model->id])->all();
    ?>
    <p>
        <?= Html::a('Добавить', ['/audios/create?lib_id='.$model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <table class="table mt-5">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Название</th>
            <th scope="col">Ссылка</th>
            <th scope="col">Статус</th>
            <th scope="col">Изменить</th>
            <th scope="col">Удалить</th>
        </tr>
        </thead>
        <tbody>
        <?
        $i = 0;
        foreach ($audios as $audio) {
            $i++;
            ?>
            <tr>
                <th scope="row"><?=$i?></th>
                <td><?=$audio['title']?></td>
                <td><?=$audio['link']?></td>
                <td><?
                    if($audio['status'] == 1){
                        echo "Опубликовано";
                    }
                    else{
                        echo "Скрыто";
                    }
                    ?></td>
                <td>
                    <a href="<?= "/audios/update?id=" . $audio['id']?>">Изменить</a>
                </td>
                <td><?=
                        Html::a('Удалить', ['/audios/delete', 'id' => $audio['id']], [
                                'data' => [
                                    'method' => 'post'
                                ]
                        ]);
                    ?>
                </td>
            </tr>
        <? }?>

        </tbody>
    </table>

</div>
