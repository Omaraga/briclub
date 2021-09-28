<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = 'Advcash - выводы';
$this->params['breadcrumbs'][] = array('label' => 'Главная Advcash', 'url' => Url::to(['/advcash/main/index']));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <h4>Ваши кошельки:</h4>
        <?php foreach($balanses as $balance):?>
            <div>
            <p>Кошелек: <b><?=$balance['id']?></b></p>
            <p>Сумма: <b><?=$balance['amount']?></b></p>
            </div>
        <?php endforeach?>
    </div>
    <?php
    $form = ActiveForm::begin();?>
        <div class="col-xs-12">
            <h4>Поиск пользователя в Advcash:</h4>
            <?php if (isset($post['user_account'])):?>
            <p>Пользователь <?=$post['user_account']?> <b><?=($user_account['present']) ? 'найден' : 'не найден'?></b></p>
            <?php endif?>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <?=Html::textInput('user_account', (isset($post['user_account'])) ? $post['user_account'] : null, array('class' => 'form-control', 'placeholder' => 'Почта юзера'));?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    <?php
    ActiveForm::end();
    ?>
</div>

<div class="row">
    <?php
    $form = ActiveForm::begin();?>
        <div class="col-xs-12">
            <h4>Проверка возможности вывода средств (деньги не отправляет):</h4>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <?=Html::textInput('amount', (isset($post['amount'])) ? $post['amount'] : null, array('class' => 'form-control', 'placeholder' => 'Сумма перевода'));?>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <?=Html::textInput('email', (isset($post['email'])) ? $post['email'] : null, array('class' => 'form-control', 'placeholder' => 'Email пользователя в Advcash'));?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <?= Html::submitButton('Проверить платеж', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    <?php
    ActiveForm::end();
    ?>
</div>

<div class="row">
	<div class="table-responsive col-xs-12">
    <h4>История вывода средств:</h4>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
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
            'card_number',
            'card_expire',
            'card_holder',
            'payment_mail',
            'response',
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
            ],
            [
                'attribute' => 'Actions',
                'format' => 'raw',
                'value' => function($data) {
                    $str = '';
                    $class = (($data->status != 'P')) ? 'disabled' : 'primary';
                    $str .= Html::a('Провести платеж', [
                            '/advcash/advcash-api/send-money', 
                            'id' => $data->id
                        ], 
                        [
                            'class' => 'btn btn-'.$class, 
                            'onclick' => 'confirm("Вы хотите провести платеж?")'
                        ]);

                    $class = (($data->status != 'P')) ? 'disabled' : 'default';
                    $str .= Html::a('Отменить платеж', [
                        '/advcash/advcash-api/cancel-withdraw', 
                        'id' => $data->id
                    ], 
                    [
                        'class' => 'btn btn-'.$class, 
                        'onclick' => 'confirm("Вы хотите отменить?")'
                    ]);                    

                    $class = (($data->status != 'P')) ? 'disabled' : 'default';
                    $str .= Html::a('Выданы на руки', [
                        '/advcash/advcash-api/send-fake-money', 
                        'id' => $data->id
                    ], 
                    [
                        'class' => 'btn btn-'.$class, 
                        'onclick' => 'confirm("Выданы на руки?")'
                    ]);
                    
                    return $str;
                }
            ]
        ],
    ]);
    ?>
    </div>
</div>