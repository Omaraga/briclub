<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = 'Advcash поступления';
$this->params['breadcrumbs'][] = array('label' => 'Главная Advcash', 'url' => Url::to(['/advcash/main/index']));
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['class' => 'table-responsive'],
    'layout' => "{items}\n{pager}\n{summary}",
    'filterModel' => $searchModel,
    'columns' => [
        [
            'label' => Yii::t('app', 'User'),
            'format' => 'raw',
            'attribute' => 'username',
            'value' => function($data){
                return Html::a($data->user->username, Url::to(['/user-infos/view', 'user_id' => $data->user->id]), ['target' => '_blank']);
            },
        ],
        [
            'attribute' => 'status',
            'filter' => Html::activeDropDownList($searchModel, 'status',
                $searchModel->statuses,
                ['class' => 'form-control', 'prompt' => 'Все']
            ),
            'value' => 'statusName',
        ],
        'sum',
        [
            'attribute' => 'created_at',
            'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
        ],
        [
            'attribute' => 'updated_at',
            'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
        ],
    ],
]);
?>
