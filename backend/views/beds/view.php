<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Beds */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$statuses = \common\models\BedStatuses::find()->all();
?>
<div class="beds-view">
        <div>
            <?
            if($model['access'] == null){?>
                <a class="btn btn-success" href="/beds/access?id=<?=$model['id']?>">Открыть доступ</a>
                <br>
                <br>
            <?}elseif($model['access'] == 1){?>
                <a class="btn btn-danger" href="/beds/access?id=<?=$model['id']?>">Закрыть доступ</a>
                <br>
                <br>
            <?}?>
        </div>

        <div>
            <form action="status" class="form-inline">
                <p>Статус:</p>
                <select name="status" class="form-control">
                    <? foreach ($statuses as $status) {?>
                        <option <?if($model->status == $status['id']){echo "selected";}?> value="<?=$status['id']?>"><?=$status['title']?></option>
                    <?}?>
                </select>
                <input type="hidden" class="form-control" name="id" value="<?=$model->id?>">
                <button type="submit" class="btn btn-success mt-1">Сохранить</button>
            </form>
            <br>
            <br>
        </div>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'email:email',
                'tel',
                [
                    'label'=>'Курс',
                    'value'=>function($data){
                        return \common\models\Courses::findOne($data['course_id'])['title'];
                    }
                ],
                [
                    'label'=>'Пользователь',
                    'value'=>function($data){
                        $user = \common\models\User::findByEmail($data['email']);
                        if(!empty($user)){return "<a href='/users/view?id=".$user['id']."'>".$user['email']."</a>";}else{return "Не создан";}
                    },
                    'format' => 'raw'
                ],
                [
                    'label'=>'Статус',
                    'value'=>function($data){
                        return \common\models\BedStatuses::findOne($data['status'])['title'];
                    }
                ],
                [
                    'label'=>'Дата заявки',
                    'value'=>function($data){
                        return date('d.m.Y H:i',$data['created_at']);
                    }
                ],
                [
                    'label'=>'Доступ',
                    'value'=>function($data){
                        if($data['access'] == 1) return "Открыт"; else return "Закрыт";
                    }
                ],
            ],
        ]) ?>

</div>
