<?php

use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страны';
$this->params['breadcrumbs'][] = $this->title;
$logins = \common\models\Countries::find()->all();
$data = array();
foreach ($logins as $item) {
    $data[] = $item['title'];
}
?>
<div class="countries-index">

    <p>
        <?= Html::a('Добавить страну', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="col-3">
        <form id="w0" action="/countries" method="get">

            <div class="form-group field-activities-username">
                <label class="control-label" for="activities-username">Страна</label>
                <?
                echo TypeaheadBasic::widget([
                    'name' => 'title',
                    'data' =>  $data,
                    'options' => ['placeholder' => 'Введите страну ...','id'=>'title','class'=>'form-control','autocomplete'=>'off'],
                    'pluginOptions' => ['highlight'=>true],
                ]);
                ?>
                <!--<input type="text" id="username" <?/*if(!empty($username)){*/?>value="<?/*=$username*/?>" <?/*}*/?> class="form-control" name="username">-->

                <div class="help-block"><?=$error?></div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Поиск</button></div>

        </form>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'title',
                'content'=>function($data){
                    return Html::a($data['title'],'/countries/update?id='.$data['id']);
                }
            ],
            'link',


        ],
    ]); ?>
</div>
