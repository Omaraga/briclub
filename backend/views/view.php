<?php

use common\models\ActionTypes;
use common\models\User;
use common\models\Beds;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
$tab = trim(Yii::$app->request->get('tab'));
$tab2 = Yii::$app->request->get('tab2');
$this->title = $model->fio;
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$courses = \common\models\Courses::find()->all();
$actions = \common\models\Actions::find()->where(['user_id'=>$model->id])->orderBy('id desc')->all();
$withdraws = \common\models\Withdraws::find()->where(['user_id'=>$model->id])->orderBy('id desc')->all();
$user = $model;
$mat1 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>1])->orderBy('id asc')->one();
$mat2 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>2])->orderBy('id asc')->one();
$mat3 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>3])->orderBy('id asc')->one();
//$mat4 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>4])->orderBy('id asc')->one();
//$mat5 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>5])->orderBy('id asc')->one();
//$mat6 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>6])->orderBy('id asc')->one();

if($user['global'] == 1){
    $matglobal = \common\models\UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
    if(!empty($matglobal)){
        $children1 = \common\models\UserPlatforms::find()->where(['parent_id'=>$matglobal['id']])->all();
    }

}
if($user['start'] == 1){
    $matstart = \common\models\MatrixStart::find()->where(['user_id'=>$user['id']])->orderBy('platform_id desc')->all();
}

if($user['newmatrix'] == 1){
    $matpersonal = \common\models\MatrixRef::find()->where(['user_id'=>$user['id']])->orderBy('platform_id desc')->all();
}


$refs = \common\models\User::find()->where(['parent_id'=>$user['id']])->all();
$binars = \common\models\UserPromotions::find()->where(['user_id'=>$user['id']])->orderBy('pr_id desc')->all();
$st = true;
if($user['platform_id'] == 1){
    if(count($refs)<2){
        $st = false;
    }
}
?>
<style>
    .tree span:hover {
        font-weight: bold;
    }

    .tree span {
        cursor: pointer;
    }
    .mat-info{
        border: 1px solid #efc95e;
        width: 200px;
        padding: 7px;
        margin: 9px;
        display: block;
        background-color: #fff;
        border-radius: 10px;
    }
    .ul-item{
        padding-left: 100px;
    }
    .mine{
        background-color: #c6ffe2!important;
    }
</style>
<div class="user-view">
    <p>
        <?
        if(User::isAccess('superadmin')) {
            echo Html::a('Изменить данные', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        ?>
    </p>
    <p>
        <?
        if(User::isAccess('superadmin')) {
            echo Html::a('Пополнить баланс', ['/deposit/create', 'username' => $model->username], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
    <p>
        <?
        if(User::isAccess('superadmin')) {
            $block = $user['block'];
            if($block == 1){
                echo Html::a('Снять блокировку', ['/users/block', 'id' => $model->id], ['class' => 'btn btn-success']);
            }else{
                echo Html::a('Блокировка снятия и переводов', ['/users/block', 'id' => $model->id], ['class' => 'btn btn-danger']);
            }

        }
        ?>
    </p>
    <p>
        <?
        if(User::isAccess('superadmin')) {?>
            <a target="_blank" href="/users-list/index?UsersSearch%5Bstructure%5D=<?=$model->username?>" class="btn btn-primary">Смотреть структуру</a>
        <?}
        ?>
    </p>
    <p>
        <?
        if(User::isAccess('superadmin')) {
            echo Html::a('Активировать премиум-аккаунт', ['/premiums/create', 'id' => $model->id], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
	<p>
        <?
        if(User::isAccess('superadmin')) {
            echo Html::a('Отменить последнее пополнение', ['/users/cancel-deposit', 'id' => $model->id], ['class' => 'btn btn-danger']);
        }
        ?>
    </p>
    <p>
        <?/*
        if(User::isAccess('superadmin')) {?>
    <div class="col-4">
        <form action="/users/giveticket" class="form">
            <input type="hidden" name="user_id" value="<?=$user['id']?>" id="">
            <select class="form-control" name="type_id" id="">
                <option value="4">15 GRC</option>
                <option value="3">45 GRC</option>
                <option value="2">65 GRC</option>
                <option value="1">115 GRC</option>
            </select>
            <button type="submit" class="btn btn-primary">Отправить билет</button>
        </form>
    </div>

        <?}*/
        ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			'id',
            [
                'label' => 'Аккаунт',
                'value' => function($data){
                    $premium = \common\models\Premiums::findOne(['user_id' => $data['id']]);
                    if($premium){
                        if($premium->is_active == 0){
                            return "Premium: истек";
                        }
                        if($premium->tariff_id == 6){
                            return "Premium: навсегда";
                        }
                        $date = date("d.m.Y H:i", $premium->expires_at + $premium->time);
                        return "Premium: истекает " . $date;
                    }
                    else{
                        return "Базовый";
                    }
                }
            ],
            'email:email',
            'username',

            'fio',
            'phone',
            [
                'label'=>'Страна',
                'value'=>function($data){
                    $country = \common\models\Countries::findOne($data['country_id'] );
                    return $country['title'];
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Баланс',
                'value'=>function($data){
                    return $data['w_balans'];
                },
            ],
            [
                'label'=>'Токены',
                'value'=>function($data){
                    $balans = \common\models\Tokens::find()->where(['user_id'=>$data['id']])->sum('balans');
                    $main = \common\models\Tokens::find()->where(['user_id'=>$data['id'],'wallet_type'=>7])->sum('balans');
                    $bonus = \common\models\Tokens::find()->where(['user_id'=>$data['id'],'wallet_type'=>8])->sum('balans');
                    return $balans." Из них основных: ".$main.", бонусных: ".$bonus;
                },
            ],
            [
                'label'=>'Заблокированая сумма',
                'value'=>function($data){
                    return $data['b_balans'];
                },
            ],

            'overdraft',
            [
                'label'=>'Спонсор',
                'value'=>function($data){
                    $parent = \common\models\User::findOne($data['parent_id'] );
                    return Html::a($parent['username'],'/users/view?id='.$parent['id']);
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Личники',
                'value'=>function($data){
                    $children = User::find()->where(['parent_id'=>$data['id']])->count();
                    $children2 = User::find()->where(['parent_id'=>$data['id'],'activ'=>1])->count();
                    return $children;
                },
            ],
            [
                'label'=>'Активированные личники',
                'value'=>function($data){
                    $children2 = User::find()->where(['parent_id'=>$data['id'],'activ'=>1])->count();
                    return $children2;
                },
            ],
            [
                'label'=>'Парнеров в структуре',
                'value'=>function($data){
                    $refs = \common\models\Referals::find()->where(['parent_id'=>$data['id']])->count();
                    return $refs;
                },
            ],
            [
                'label'=>'Активированных парнеров в структуре',
                'value'=>function($data){
                    $refs = \common\models\Referals::find()->where(['parent_id'=>$data['id'],'activ'=>1])->count();
                    return $refs;
                },
            ],
            [
                'label'=>'Подтверждение email',
                'value'=>function($data){
                    if(!empty($data['password_hash'])) return "Подтвержден"; else return "Не подтвержден";
                }
            ],
            [
                'label'=>'Матрица (Максимальный уровень)',
                'value'=>function($data){
                    $res = "";
                    if($data['newmatrix'] == 1){
                        $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Shanyrak+ (Уровень ".$level['platform_id'].")";
                    }
                    return $res;
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Блокирован',
                'value'=>function($data){
                    if($data['block'] == 1){
                        return "Да";
                    }else{
                        return "Нет";
                    }
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Активирован',
                'value'=>function($data){
                    if($data['activ'] == 1){
                        return "Да";
                    }else{
                        return "Нет";
                    }
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Дата регистрации',
                'value'=>function($data){
                    return date("d.m.Y H:i",$data['created_at']);
                }
            ],

            [
                'label'=>'Дата активации Shanyrak+',
                'value'=>function($data){
                    if(!empty($data['time_personal'])){
                        return date("d.m.Y H:i",$data['time_personal']);
                    }
                }
            ],
            [
                'label'=>'Отчет по балансу',
                'value'=>function($data){
                    $acts = \common\models\Actions::find()->where(['user_id'=>$data['id']])->all();
                    $sum = 0;
                    $sum = number_format($sum,2, '.', '');
                    foreach ($acts as $act) {
                        if($act['type'] == 2) continue;
                        $type = ActionTypes::findOne($act['type']);
                        if($type['minus'] == 1){
                            $sum = $sum - $act['sum'];
                        }else{
                            $sum = $sum + $act['sum'];
                        }
                    }

                    $withs = \common\models\Withdraws::find()->where(['user_id'=>$data['id']])->all();
                    foreach ($withs as $with) {
                        if(!empty($with['sum'])){
                            $sum = $sum - $with['sum'];
                        }

                    }
                    $balans = $data['w_balans'] + $data['b_balans'];
                    $sum = number_format($sum,2, '.', '');
                    $balans = number_format($balans,2, '.', '');
                    /*var_dump($sum);
                    exit;*/
                    if($sum == $balans){
                        return "Баланс правильный";
                    }else{
                        return "Не соответсвие баланса на ".($balans-$sum);
                    }
                }
            ],
            [
                'label'=>'Отчет по токенам',
                'value'=>function($data){
                    return \common\models\Tokens::checkTokenBalans($data['id']);
                }
            ],
            [

                'label' => 'Статистика структуры',
                'format' => 'raw',
                'value' => function($data){
                    return  Html::a('Посмотреть: '.$data['username'], ['actions/structure-payments', 'userId' => $data['id']], ['class' => 'profile-link']);
                },
            ],

        ],
    ]) ?>
    <br>

    <br>
    <ul class="nav nav-tabs">
        <li class="<?if($tab == "panel2"){echo "active";}?>"><a href="/users/view?id=<?=$user['id']?>&tab=panel2">Операции</a></li>
        <li class="<?if($tab == "panel3"){echo "active";}?>"><a  href="/users/view?id=<?=$user['id']?>&tab=panel3">Структура</a></li>
        <li class="<?if($tab == "panel4"){echo "active";}?>"><a  href="/users/view?id=<?=$user['id']?>&tab=panel4">Матрица</a></li>
        <li class="<?if($tab == "panel6"){echo "active";}?>"><a  href="/users/view?id=<?=$user['id']?>&tab=panel6">План на контракты</a></li>
        <li class="<?if($tab == "panel7"){echo "active";}?>"><a  href="/users/view?id=<?=$user['id']?>&tab=panel7">Заработок для компании</a></li>
    </ul>

    <div class="tab-content">
        <?if($tab == "panel2"){?>
            <div id="panel2" class="tab-pane fade in active">
                <h3>Операции</h3>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#panel100">Вся активность</a></li>
                    <li><a data-toggle="tab" href="#panel11">Пополнения</a></li>
                    <li><a data-toggle="tab" href="#panel14">Переводы</a></li>
                    <li><a data-toggle="tab" href="#panel13">Поступления</a></li>
                    <li><a data-toggle="tab" href="#panel17">Выводы</a></li>
                    <li><a data-toggle="tab" href="#panel55">Токены</a></li>
                    <li><a data-toggle="tab" href="#panel188">Пополнения Perfect</a></li>
                    <li><a data-toggle="tab" href="#panel189">Visa</a></li>

                </ul>

                <div class="tab-content">
                    <div id="panel100" class="tab-pane fade in active">
                        <h3>Вся активность</h3>
                        <table class="table mt-5">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Аккаунт</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Токены</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Описание</th>
                                <th scope="col">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            foreach ($actions as $withdraw) {
                                $i++;
                                ?>
                                <tr>
                                    <th scope="row"><?=$i?></th>
                                    <td><?=\common\models\User::findOne($withdraw['user_id'])['username']?></td>
                                    <td style="text-align: right">
                                        <?
                                        $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                        if($type['minus'] == 1){?>
                                            <span style="color: red">- <?=$withdraw['sum']?></span>
                                        <?}else{?>
                                            <span style="color: green"> <?=$withdraw['sum']?></span>
                                        <?}?>
                                    </td>
                                    <td><?
                                        $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                        if($type['minus'] == 1){?>
                                            <span style="color: red">- <?=$withdraw['tokens']?></span>
                                        <?}else{?>
                                            <span style="color: green"> <?=$withdraw['tokens']?></span>
                                        <?}?></td>
                                    <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                    <td>
                                        <?
                                        $color = null;
                                        $text = null;
                                        if($withdraw['status'] == 3){
                                            $color = "primary";
                                            $text = "В обработке";
                                        }elseif($withdraw['status'] == 2){
                                            $color = "danger";
                                            $text = "Отменено";
                                        }elseif($withdraw['status'] == 1){
                                            $color = "success";
                                            $text = "Выполнено";
                                        }
                                        ?>
                                        <span class="badge badge-<?=$color?>"><?=$text?></span>
                                    </td>
                                    <td>
                                        <?
                                        echo $withdraw['title'];
                                        if($withdraw['type'] == 3){
                                            echo " пользователю ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                        }elseif($withdraw['type'] == 4){
                                            echo " от пользователя ".\common\models\User::findOne($withdraw['user2_id'])['username'];
                                        }elseif($withdraw['type'] == 11 or $withdraw['type'] == 77){
                                            echo " в матрице Start";
                                        }elseif($withdraw['type'] == 1 or $withdraw['type'] == 7 or $withdraw['type'] == 9){
                                            echo " в матрице Personal";
                                        }elseif($withdraw['type'] == 8 or $withdraw['type'] == 88 or $withdraw['type'] == 10){
                                            echo " в матрице Global";
                                        }
                                        ?>
                                    </td>
                                    <td><?=$type['title']?></td>
                                </tr>
                            <? }?>

                            </tbody>
                        </table>
                    </div>
                    <div id="panel11" class="tab-pane fade">
                        <h3>Пополнения</h3>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Аккаунт</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            foreach ($actions as $withdraw) {
                                if($withdraw['type'] == 5){
                                    $i++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$i?></th>
                                        <td><?=\common\models\User::findOne($withdraw['user_id'])['username']?></td>
                                        <td><?=$withdraw['sum']?></td>
                                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                        <td>
                                            <?
                                            $color = null;
                                            $text = null;
                                            if($withdraw['status'] == 3){
                                                $color = "primary";
                                                $text = "В обработке";
                                            }elseif($withdraw['status'] == 2){
                                                $color = "danger";
                                                $text = "Отменено";
                                            }elseif($withdraw['status'] == 1){
                                                $color = "success";
                                                $text = "Выполнено";
                                            }
                                            ?>
                                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                                        </td>
                                        <td><?=$withdraw['title']?></td>
                                    </tr>
                                <? }}?>

                            </tbody>
                        </table>
                    </div>
                    <div id="panel14" class="tab-pane fade">
                        <h3>Переводы</h3>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Отправитель</th>
                                <th scope="col">Получатель</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            foreach ($actions as $withdraw) {
                                if($withdraw['type'] == 3){
                                    $i++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$i?></th>
                                        <td><?=\common\models\User::findOne($withdraw['user_id'])['username']?></td>
                                        <td><a href="/users/view?id=<?=\common\models\User::findOne($withdraw['user2_id'])['id']?>"><?=\common\models\User::findOne($withdraw['user2_id'])['username']?></td>
                                        <td><?=$withdraw['sum']?></td>
                                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                        <td>
                                            <?
                                            $color = null;
                                            $text = null;
                                            if($withdraw['status'] == 3){
                                                $color = "primary";
                                                $text = "В обработке";
                                            }elseif($withdraw['status'] == 2){
                                                $color = "danger";
                                                $text = "Отменено";
                                            }elseif($withdraw['status'] == 1){
                                                $color = "success";
                                                $text = "Выполнено";
                                            }
                                            ?>
                                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                                        </td>
                                        <td><?=$withdraw['title']?></td>
                                    </tr>
                                <? }}?>

                            </tbody>
                        </table>
                    </div>
                    <div id="panel13" class="tab-pane fade">
                        <h3>Поступления</h3>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Отправитель</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            foreach ($actions as $withdraw) {
                                if($withdraw['type'] == 4){
                                    $i++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$i?></th>
                                        <td><a href="/users/view?id=<?=\common\models\User::findOne($withdraw['user2_id'])['id']?>"><?=\common\models\User::findOne($withdraw['user2_id'])['username']?></td>
                                        <td><?=$withdraw['sum']?></td>
                                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                        <td>
                                            <?
                                            $color = null;
                                            $text = null;
                                            if($withdraw['status'] == 3){
                                                $color = "primary";
                                                $text = "В обработке";
                                            }elseif($withdraw['status'] == 2){
                                                $color = "danger";
                                                $text = "Отменено";
                                            }elseif($withdraw['status'] == 1){
                                                $color = "success";
                                                $text = "Выполнено";
                                            }
                                            ?>
                                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                                        </td>
                                        <td><?=$withdraw['title']?></td>
                                    </tr>
                                <? }}?>

                            </tbody>
                        </table>
                    </div>
                    <div id="panel17" class="tab-pane fade">
                        <h3>Выводы</h3>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Счет</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Комиссия</th>
                                <th scope="col">На вывод</th>
                                <th scope="col">Платежная система</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            foreach ($withdraws as $withdraw) {
                                $i++;
                                ?>
                                <tr>
                                    <th scope="row"><?=$i?></th>
                                    <td><?=$withdraw['account']?></td>
                                    <td><?=$withdraw['sum']?></td>
                                    <td><?=$withdraw['fee']?></td>
                                    <td><?=$withdraw['sum2']?></td>
                                    <td>
                                        <?
                                        if($withdraw['system_id'] == 1){
                                            echo "Advcash";
                                        }else{
                                            echo "Perfect Money";
                                        } ?>
                                    </td>
                                    <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                    <td>
                                        <?
                                        if($withdraw['status'] == 3){
                                            $color = "primary";
                                            $text = "В обработке";
                                        }elseif($withdraw['status'] == 2){
                                            $color = "danger";
                                            $text = "Отменено";
                                        }elseif($withdraw['status'] == 1){
                                            $color = "success";
                                            $text = "Выполнено";
                                        }
                                        ?>
                                        <span class="badge badge-<?=$color?>"><?=$text?></span>
                                    </td>
                                </tr>
                            <? }?>

                            </tbody>
                        </table>
                    </div>
                    <div id="panel55" class="tab-pane fade">
                        <h3>Токены</h3>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Отправитель</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Токены</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Описание</th>
                                <th scope="col">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            $types_minus = ActionTypes::find()->where(['cat'=>5])->all();
                            $token_types = [];
                            foreach ($types_minus as $types_min) {
                                $token_types[] = $types_min['id'];
                            }

                            foreach ($actions as $withdraw) {
                                if(in_array($withdraw['type'],$token_types)){
                                    $i++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$i?></th>
                                        <td><a href="/users/view?id=<?=\common\models\User::findOne($withdraw['user2_id'])['id']?>"><?=\common\models\User::findOne($withdraw['user2_id'])['username']?></td>
                                        <td><?=$withdraw['sum']?></td>
                                        <td><?
                                            $type = \common\models\ActionTypes::findOne($withdraw['type']);
                                            if($type['minus'] == 1){?>
                                                <span style="color: red">- <?=$withdraw['tokens']?></span>
                                            <?}else{?>
                                                <span style="color: green"> <?=$withdraw['tokens']?></span>
                                            <?}?></td>
                                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                        <td>
                                            <?
                                            $color = null;
                                            $text = null;
                                            if($withdraw['status'] == 3){
                                                $color = "primary";
                                                $text = "В обработке";
                                            }elseif($withdraw['status'] == 2){
                                                $color = "danger";
                                                $text = "Отменено";
                                            }elseif($withdraw['status'] == 1){
                                                $color = "success";
                                                $text = "Выполнено";
                                            }
                                            ?>
                                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                                        </td>
                                        <td><?=$withdraw['title']?></td>
                                        <td><?=$type['title']?></td>
                                    </tr>
                                <? }}?>

                            </tbody>
                        </table>
                    </div>
                    <div id="panel188" class="tab-pane fade">
                        <h3>Пополнения через Perfect</h3>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Аккаунт</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            foreach ($actions as $withdraw) {
                                if($withdraw['type'] == 6){
                                    $i++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$i?></th>
                                        <td><?=\common\models\User::findOne($withdraw['user_id'])['username']?></td>
                                        <td><?=$withdraw['sum']?></td>
                                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                        <td>
                                            <?
                                            $color = null;
                                            $text = null;
                                            if($withdraw['status'] == 3){
                                                $color = "primary";
                                                $text = "В обработке";
                                            }elseif($withdraw['status'] == 2){
                                                $color = "danger";
                                                $text = "Отменено";
                                            }elseif($withdraw['status'] == 1){
                                                $color = "success";
                                                $text = "Выполнено";
                                            }
                                            ?>
                                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                                        </td>
                                        <td><?=$withdraw['title']?></td>
                                    </tr>
                                <? }}?>

                            </tbody>
                        </table>
                    </div>
                    <div id="panel189" class="tab-pane fade">
                        <h3>Пополнения через Perfect Visa</h3>
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Аккаунт</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Время</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Тип</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 0;
                            foreach ($actions as $withdraw) {
                                if($withdraw['type'] == 102){
                                    $i++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$i?></th>
                                        <td><?=\common\models\User::findOne($withdraw['user_id'])['username']?></td>
                                        <td><?=$withdraw['sum']?></td>
                                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                        <td>
                                            <?
                                            $color = null;
                                            $text = null;
                                            if($withdraw['status'] == 3){
                                                $color = "primary";
                                                $text = "В обработке";
                                            }elseif($withdraw['status'] == 2){
                                                $color = "danger";
                                                $text = "Отменено";
                                            }elseif($withdraw['status'] == 1){
                                                $color = "success";
                                                $text = "Выполнено";
                                            }
                                            ?>
                                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                                        </td>
                                        <td><?=$withdraw['title']?></td>
                                    </tr>
                                <? }}?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        <?}?>
        <?if($tab == "panel3"){?>
            <div id="panel3" class="tab-pane  fade in active">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#panel200">Структура</a></li>
                    <li><a data-toggle="tab" href="#panel210">Спонсоры</a></li>

                </ul>

                <div class="tab-content">
                    <div id="panel200" class="tab-pane fade in active">
                        <h3>Личники</h3>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=title" data-sort="title">Логин</a>
                                </th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=email" data-sort="email">Линия</a>
                                </th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=email" data-sort="email">Спонсор</a>
                                </th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Матрица</a>
                                </th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Баланс</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $children = \common\models\Referals::find()->where(['parent_id'=>$model->id])->orderBy('level asc')->all();
                            $i = 0;
                            foreach ($children as $child) {
                                $cours = User::findOne($child['user_id']);
                                $ch_user = User::findOne($child['user_id']);
                                $i++;
                                ?>
                                <tr data-key="36">
                                    <td><?=$i?></td>
                                    <td><a href="/users/view?id=<?=$cours['id']?>"><?=$cours['username']?></a></td>
                                    <td>
                                        <?=$child['level']?>
                                    </td>
                                    <td>
                                        <a href="/users/view?id=<?=$cours['parent_id']?>"><?=User::findOne($cours['parent_id'])['username']?></a>
                                    </td>
                                    <td>
                                        <?
                                        $res = "";
                                        if($cours['start'] == 1){
                                            $level = \common\models\MatrixStart::find()->where(['user_id'=>$cours['id']])->orderBy('platform_id desc')->one();
                                            $res = $res." Start (Уровень ".$level['platform_id'].")";
                                        }
                                        if($cours['newmatrix'] == 1){
                                            $level = \common\models\MatrixRef::find()->where(['user_id'=>$cours['id']])->orderBy('platform_id desc')->one();
                                            $res = $res." Personal (Уровень ".$level['platform_id'].")";
                                        }
                                        if($cours['global'] == 1){
                                            $level = \common\models\UserPlatforms::find()->where(['user_id'=>$cours['id']])->orderBy('platform_id desc')->one();
                                            $res = $res." Global (Уровень ".($level['platform_id']-1).")";
                                        }
                                        echo $res;?>
                                    </td>
                                    <td>
                                        <?=$ch_user['w_balans']?>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                    <div id="panel210" class="tab-pane fade">
                        <h3>Спонсоры</h3>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=title" data-sort="title">Логин</a>
                                </th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=email" data-sort="email">Линия</a>
                                </th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=email" data-sort="email">Спонсор</a>
                                </th>
                                <th>
                                    <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Матрица</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $children = \common\models\Referals::find()->where(['user_id'=>$model->id])->orderBy('level asc')->all();
                            $i = 0;
                            foreach ($children as $child) {
                                $cours = User::findOne($child['parent_id']);
                                $i++;
                                ?>
                                <tr data-key="36">
                                    <td><?=$i?></td>
                                    <td><a href="/users/view?id=<?=$cours['id']?>"><?=$cours['username']?></a></td>
                                    <td>
                                        <?=$child['level']?>
                                    </td>
                                    <td>
                                        <a href="/users/view?id=<?=$cours['parent_id']?>"><?=User::findOne($cours['parent_id'])['username']?></a>
                                    </td>
                                    <td>
                                        <?
                                        $res = "";
                                        if($cours['start'] == 1){
                                            $level = \common\models\MatrixStart::find()->where(['user_id'=>$cours['id']])->orderBy('platform_id desc')->one();
                                            $res = $res." Start (Уровень ".$level['platform_id'].")";
                                        }
                                        if($cours['newmatrix'] == 1){
                                            $level = \common\models\MatrixRef::find()->where(['user_id'=>$cours['id']])->orderBy('platform_id desc')->one();
                                            $res = $res." Personal (Уровень ".$level['platform_id'].")";
                                        }
                                        if($cours['global'] == 1){
                                            $level = \common\models\UserPlatforms::find()->where(['user_id'=>$cours['id']])->orderBy('platform_id desc')->one();
                                            $res = $res." Global (Уровень ".($level['platform_id']-1).")";
                                        }
                                        echo $res;?>
                                    </td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        <?}?>
        <?if($tab == "panel4"){?>
            <div id="panel4" class="tab-pane  fade in active">
                <h3>Матрица</h3>
                <ul class="nav nav-tabs">
                    <li class="<?if($tab2 == "mat1"){echo "active";}?>"><a href="/users/view?id=<?=$user['id']?>&tab=panel4&tab2=mat1">Стол 1</a></li>
                    <li class="<?if($tab2 == "mat2"){echo "active";}?>"><a href="/users/view?id=<?=$user['id']?>&tab=panel4&tab2=mat2">Стол 2</a></li>
                    <li class="<?if($tab2 == "mat3"){echo "active";}?>"><a href="/users/view?id=<?=$user['id']?>&tab=panel4&tab2=mat3">Стол 3</a></li>
                    <li class=""><a target="_blank" href="/site/mat?level=4&username=<?=$user['username']?>">Стол 4</a></li>
                    <li class=""><a  target="_blank" href="/site/mat?level=5&username=<?=$user['username']?>">Стол 5</a></li>
                    <li class=""><a target="_blank" href="/site/mat?level=6&username=<?=$user['username']?>">Стол 6</a></li>
                </ul>

                <div class="tab-content">
                    <?if($tab2 == "mat1"){?>
                        <div id="mat1" class="tab-pane fade in active">
                            <h3>Стол 1</h3>
                            <ul class="tree" id="tree">
                                <?
                                if(!empty($mat1)){
                                    $buys = \common\models\MatrixRef::find()->where(['platform_id'=>1,'user_id'=>$user['id'],'reinvest'=>0,'buy'=>1])->all();
                                    $clons = \common\models\MatrixRef::find()->where(['platform_id'=>1,'user_id'=>$user['id'],'reinvest'=>1,'buy'=>2])->all();
                                    $acts = \common\models\MatrixRef::find()->where(['platform_id'=>1,'user_id'=>$user['id'],'reinvest'=>0,'buy'=>2])->all();
                                    $closes = \common\models\MatrixRef::find()->where(['platform_id'=>1,'user_id'=>$user['id'],'slots'=>4])->all();

                                    ?>
                                    <p>Всего активированных мест: <?=count($acts)?></p>
                                    <p>Всего выкупленных мест: <?=count($buys)?></p>
                                    <p>Всего клонов: <?=count($clons)?></p>
                                    <p>Всего закрытых мест: <?=count($closes)?></p>
                                    <?
                                    $parent = \common\models\MatrixRef::findOne($mat1['parent_id']);
                                    $parent_user = User::findOne($parent['user_id']);
                                    echo "Вышестоящее место:";?>
                                    <span class="mat-info ">
                                <? if ($parent['reinvest'] == 1) {?>
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                        <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                    </svg>
                                <?}elseif ($parent['buy'] == 1){?>
                                    <i class="fa fa-dollar"></i>
                                <?}else{?>
                                    <i class="fa fa-dashboard"></i>
                                <?}?>
                                        <?=$parent_user['username']?>
                                        <?
                                        $num1 = \common\models\MatClons::find()->where(['mat_id'=>$parent['id']])->one();
                                        if(!empty($num1)){?>
                                            (<?=$num1['num']?>)
                                        <?}
                                        ?>
                                [<?=$parent['id']?>]
                            </span>
                                    <? \backend\controllers\SiteController::getDom($mat1['id'],$user['id']);
                                }

                                ?>
                            </ul>
                        </div>
                    <?}?>
                    <?if($tab2 == "mat2"){?>
                        <div id="mat2" class="tab-pane  fade in active">
                            <h3>Стол 2</h3>
                            <ul class="tree2" id="tree2">
                                <?
                                if(!empty($mat2)){
                                    $buys2 = \common\models\MatrixRef::find()->where(['platform_id'=>2,'user_id'=>$user['id'],'reinvest'=>0,'buy'=>1])->all();
                                    $clons2 = \common\models\MatrixRef::find()->where(['platform_id'=>2,'user_id'=>$user['id'],'reinvest'=>1,'buy'=>2])->all();
                                    $acts2 = \common\models\MatrixRef::find()->where(['platform_id'=>2,'user_id'=>$user['id'],'reinvest'=>0,'buy'=>2])->all();
                                    $closes2 = \common\models\MatrixRef::find()->where(['platform_id'=>2,'user_id'=>$user['id'],'slots'=>4])->all();

                                    ?>
                                    <p>Всего активированных мест: <?=count($acts2)?></p>
                                    <p>Всего выкупленных мест: <?=count($buys2)?></p>
                                    <p>Всего клонов: <?=count($clons2)?></p>
                                    <p>Всего закрытых мест: <?=count($closes2)?></p>
                                    <?
                                    $parent2 = \common\models\MatrixRef::findOne($mat2['parent_id']);
                                    $parent_user2 = User::findOne($parent2['user_id']);
                                    echo "Вышестоящее место:";?>
                                    <span class="mat-info ">
                                <? if ($parent2['reinvest'] == 1) {?>
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                        <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                    </svg>
                                <?}elseif ($parent2['buy'] == 1){?>
                                    <i class="fa fa-dollar"></i>
                                <?}else{?>
                                    <i class="fa fa-dashboard"></i>
                                <?}?>
                                        <?=$parent_user2['username']?>
                                        <?
                                        $num1 = \common\models\MatClons::find()->where(['mat_id'=>$parent2['id']])->one();
                                        if(!empty($num1)){?>
                                            (<?=$num1['num']?>)
                                        <?}
                                        ?>
                                [<?=$parent2['id']?>]
                            </span>
                                    <?
                                    \backend\controllers\SiteController::getDom($mat2['id'],$user['id']);
                                }

                                ?>
                            </ul>
                        </div>
                    <?}?>
                    <?if($tab2 == "mat3"){?>
                        <div id="mat3" class="tab-pane  fade in active">
                            <h3>Стол 3</h3>
                            <ul class="tree3" id="tree3">
                                <?
                                if(!empty($mat3)){
                                    $buys3 = \common\models\MatrixRef::find()->where(['platform_id'=>3,'user_id'=>$user['id'],'reinvest'=>0,'buy'=>1])->all();
                                    $clons3 = \common\models\MatrixRef::find()->where(['platform_id'=>3,'user_id'=>$user['id'],'reinvest'=>1,'buy'=>2])->all();
                                    $acts3 = \common\models\MatrixRef::find()->where(['platform_id'=>3,'user_id'=>$user['id'],'reinvest'=>0,'buy'=>2])->all();
                                    $closes3 = \common\models\MatrixRef::find()->where(['platform_id'=>3,'user_id'=>$user['id'],'slots'=>4])->all();

                                    ?>
                                    <p>Всего активированных мест: <?=count($acts3)?></p>
                                    <p>Всего выкупленных мест: <?=count($buys3)?></p>
                                    <p>Всего клонов: <?=count($clons3)?></p>
                                    <p>Всего закрытых мест: <?=count($closes3)?></p>
                                    <?
                                    $parent3 = \common\models\MatrixRef::findOne($mat3['parent_id']);
                                    $parent_user3 = User::findOne($parent3['user_id']);
                                    echo "Вышестоящее место:";?>
                                    <span class="mat-info ">
                                <? if ($parent3['reinvest'] == 1) {?>
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                        <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                    </svg>
                                <?}elseif ($parent3['buy'] == 1){?>
                                    <i class="fa fa-dollar"></i>
                                <?}else{?>
                                    <i class="fa fa-dashboard"></i>
                                <?}?>
                                        <?=$parent_user3['username']?>
                                        <?
                                        $num1 = \common\models\MatClons::find()->where(['mat_id'=>$parent3['id']])->one();
                                        if(!empty($num1)){?>
                                            (<?=$num1['num']?>)
                                        <?}
                                        ?>
                                [<?=$parent3['id']?>]
                            </span>
                                    <?
                                    \backend\controllers\SiteController::getDom($mat3['id'],$user['id']);
                                }

                                ?>
                            </ul>
                        </div>
                    <?}?>
                </div>
            </div>
        <?}?>
        <?if($tab == "panel6"){?>
            <div id="panel6" class="tab-pane  fade in active">
                <h3>План на контракты</h3>
                <p>
                    <?
                    $plan = \common\models\UserPlans::find()->where(['user_id'=>$user['id']])->one();
                    if(User::isAccess('superadmin') and empty($plan)) {
                        echo Html::a('Добавить план', ['/user-plans/create', 'user_id' => $model->id], ['class' => 'btn btn-primary']);
                    }
                    ?>
                </p>
                <?if(!empty($plan)){?>
                    <table class="table table-striped table-bordered"><thead>
                        <tr>
                            <th>
                                <a href="/users/view?id=58&amp;sort=title" data-sort="title">Количество Start</a>
                            </th>
                            <th>
                                <a href="/users/view?id=58&amp;sort=email" data-sort="email">Количество Personal</a>
                            </th>
                            <th>
                                <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Количество Global</a>
                            </th>
                            <th>
                                <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Награда</a>
                            </th>
                            <th>
                                <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Изменить</a>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-key="36">
                            <td><?=$plan['start']?></td>
                            <td><?=$plan['personal']?></td>
                            <td><?=$plan['global']?></td>
                            <td><?=$plan['sum']?></td>
                            <td><a href="/user-plans/update?id=<?=$plan['id']?>">Изменить</a></td>
                        </tr>
                        </tbody>
                    </table>
                <?}?>
            </div>
        <?}?>
        <?if($tab == "panel7"){?>
            <div id="panel7" class="tab-pane  fade in active">
                <h3>Заработок для компании</h3>
                <p>
                    Стол 1:
                    <br>
                    <?

                    $all = 0;
                    $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>1,'reinvest'=>0])->all();
                    if(!empty($mats)){
                        foreach ($mats as $mat) {
                            echo "Заработок за место [".$mat['id']."]"." $3<br>";
                            $all +=3;
                        }
                    }
                    echo "Общий заработок с 1 стола: $".$all;
                    ?>
                </p>
                <p>
                    Стол 4:
                    <br>
                    <?

                    $all4 = 0;
                    $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>4,'slots'=>1])->all();
                    if(!empty($mats)){
                        foreach ($mats as $mat) {
                            echo "Заработок за место [".$mat['id']."]"." $100<br>";
                            $all4 +=100;
                        }
                    }
                    echo "Общий заработок с 4 стола: $".$all4;
                    ?>
                </p>
                <p>
                    Стол 5:
                    <br>
                    <?

                    $all5 = 0;
                    $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>5,'slots'=>1])->all();
                    if(!empty($mats)){
                        foreach ($mats as $mat) {
                            echo "Заработок за место [".$mat['id']."]"." $400<br>";
                            $all5 +=400;
                        }
                    }
                    echo "Общий заработок с 5 стола: $".$all5;
                    ?>
                </p>
                <p>
                    Стол 6:
                    <br>
                    <?

                    $all6 = 0;
                    $mats = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>6,'slots'=>1])->all();
                    if(!empty($mats)){
                        foreach ($mats as $mat) {
                            echo "Заработок за место [".$mat['id']."]"." $800<br>";
                            $all6 +=800;
                        }
                    }
                    $mats2 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>6,'slots'=>3])->all();
                    if(!empty($mats2)){
                        foreach ($mats2 as $mat2) {
                            echo "Заработок за место [".$mat2['id']."]"." $16200<br>";
                            $all6 +=16200;
                        }
                    }
                    $mats3 = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>6,'slots'=>4])->all();
                    if(!empty($mats3)){
                        foreach ($mats3 as $mat3) {
                            echo "Заработок за место [".$mat3['id']."]"." $16200<br>";
                            $all6 +=16200;
                        }
                    }
                    echo "Общий заработок с 6 стола: $".$all6;
                    echo "<br><br>Общий заработок со всех столов: $".($all+$all4+$all5+$all6);
                    ?>
                </p>
            </div>
        <?}?>

    </div>
</div>
<script>
    // поместить все текстовые узлы в элемент <span>
    // он занимает только то место, которое необходимо для текста
    for (let li of tree.querySelectorAll('li')) {
        let span = document.createElement('span');
        li.prepend(span);
        span.append(span.nextSibling); // поместить текстовый узел внутрь элемента <span>
    }

    //  ловим клики на всём дереве
    tree.onclick = function(event) {

        if (event.target.tagName != 'SPAN') {
            return;
        }

        let childrenContainer = event.target.parentNode.querySelector('ul');
        if (!childrenContainer) return; // нет детей

        childrenContainer.hidden = !childrenContainer.hidden;
    }
</script>