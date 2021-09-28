<?php

use common\models\User;
use common\models\Beds;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->fio;
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$courses = \common\models\Courses::find()->all();
$actions = \common\models\Actions::find()->where(['user_id'=>$model->id])->orderBy('id desc')->all();
$withdraws = \common\models\Withdraws::find()->where(['user_id'=>$model->id])->orderBy('id desc')->all();
$user = $model;

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
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
            'username',
            'order',
            'fio',
            'phone',
            'w_balans',
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
                'label'=>'Матрица',
                'value'=>function($data){
                    $res = "";
                    if($data['start'] == 1){
                        $level = \common\models\MatrixStart::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Start (Уровень ".$level['platform_id'].")";
                    }
                    if($data['newmatrix'] == 1){
                        $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Personal (Уровень ".$level['platform_id'].")";
                    }
                    if($data['global'] == 1){
                        $level = \common\models\UserPlatforms::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                        $res = $res." Global (Уровень ".($level['platform_id']-1).")";
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
                'label'=>'Дата активации старт',
                'value'=>function($data){
                    if(!empty($data['time_start'])){
                        return date("d.m.Y H:i",$data['time_start']);
                    }

                }
            ],
            [
                'label'=>'Дата активации Personal',
                'value'=>function($data){
                    if(!empty($data['time_personal'])){
                        return date("d.m.Y H:i",$data['time_personal']);
                    }
                }
            ],
            [
                'label'=>'Дата активации Global',
                'value'=>function($data){
                    if(!empty($data['time_global'])){
                        return date("d.m.Y H:i",$data['time_global']);
                    }
                }
            ],
        ],
    ]) ?>
    <br>
    <br>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#panel2">Операции</a></li>
        <li><a data-toggle="tab" href="#panel3">Структура</a></li>
        <li><a data-toggle="tab" href="#panel4">Матрица</a></li>
        <li><a data-toggle="tab" href="#panel1">Бинар</a></li>
        <li><a data-toggle="tab" href="#panel5">Доступы к курсам</a></li>
    </ul>

    <div class="tab-content">
        <div id="panel2" class="tab-pane fade in active">
            <h3>Операции</h3>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#panel100">Вся активность</a></li>
                <li><a data-toggle="tab" href="#panel11">Пополнения</a></li>
                <li><a data-toggle="tab" href="#panel14">Переводы</a></li>
                <li><a data-toggle="tab" href="#panel13">Поступления</a></li>
                <li><a data-toggle="tab" href="#panel17">Выводы</a></li>
                <li><a data-toggle="tab" href="#panel188">Пополнения Perfect</a></li>

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
                            <th scope="col">Время</th>
                            <th scope="col">Статус</th>
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
            </div>

        </div>
        <div id="panel3" class="tab-pane fade">
            <h3>Личники</h3>
            <table class="table table-striped table-bordered"><thead>
                <tr>
                    <th>#</th>
                    <th>
                        <a href="/users/view?id=58&amp;sort=title" data-sort="title">Логин</a>
                    </th>
                    <th>
                        <a href="/users/view?id=58&amp;sort=email" data-sort="email">Email</a>
                    </th>
                    <th>
                        <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Уровень</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?
                $children = \common\models\User::find()->where(['parent_id'=>$model->id])->all();
                $i = 0;
                foreach ($children as $cours) {
                    $i++;
                    ?>
                    <tr data-key="36">
                        <td><?=$i?></td>
                        <td><a href="/users/view?id=<?=$cours['id']?>"><?=$cours['username']?></a></td>
                        <td>
                            <?=$cours['email']?>
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
        <div id="panel4" class="tab-pane fade">
            <h3>Матрица</h3>
            <ul class="nav nav-tabs">
                <? if($user['start'] == 1){?>
                    <li class="active"><a data-toggle="tab" href="#matstart">Start</a></li>
                <?} ?>
                <? if($user['newmatrix'] == 1){?>
                    <li><a data-toggle="tab" href="#matper">Personal</a></li>
                <?} ?>
                <? if($user['global'] == 1){?>
                    <li><a data-toggle="tab" href="#matglob">Global</a></li>
                <?} ?>

            </ul>

            <div class="tab-content">
                <? if($user['start'] == 1){?>
                    <div id="matstart" class="tab-pane fade in active">
                        <h3>Start</h3>
                        <div class="structure-wrap">
                            <div class="row">
                                <? if(!empty($matstart)){
                                    foreach ($matstart as $item) {
                                        ?>
                                        <?if(!empty($item['parent_id'])){?>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4 offset-4">
                                                    <div class="avatar-block mb-5">
                                                        <div class="avatar-icon">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0">


                                                                    <? $s_matrix_p = \common\models\MatrixStart::findOne($item['parent_id']);
                                                                    $par = \common\models\User::findOne($s_matrix_p['user_id']);
                                                                    ?>
                                                                    <a href="/users/view?id=<?=$par['id']?>">
                                                                        <?
                                                                        echo $par['username'];
                                                                        ?>
                                                                    </a>
                                                                    <?

                                                                    if($s_matrix_p['reinvest'] == 1){
                                                                        echo "REINVEST";
                                                                    }
                                                                    if($s_matrix_p['deleted'] == 1){
                                                                        echo "History";
                                                                    }
                                                                    ?>
                                                                    platform
                                                                    <?=$s_matrix_p['platform_id']?>


                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?}?>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4 offset-4">
                                                    <div class="avatar-block mb-5">
                                                        <div class="avatar-icon">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0"><?=$user['username']?> <?if($item['reinvest'] == 1){echo "REINVEST";}?> platform <?=$item['platform_id']?></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">

                                                <div class="col-4 offset-2">
                                                    <div class="avatar-block mb-5">
                                                        <div class="avatar-icon <?if(!empty($item['shoulder1'])){?>empty-mat<?}?>">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0">
                                                                <?if(!empty($item['shoulder1'])){
                                                                    $s_matrix1 = \common\models\MatrixStart::findOne($item['shoulder1']);
                                                                    $shoulder1 = \common\models\User::findOne($s_matrix1['user_id']);
                                                                    ?>
                                                                    <a href="/users/view?id=<?=$shoulder1['id']?>">
                                                                        <?
                                                                        echo $shoulder1['username'];
                                                                        ?>
                                                                    </a>
                                                                    <?

                                                                    if($s_matrix1['reinvest'] == 1){
                                                                        echo "REINVEST";
                                                                    }
                                                                    if($s_matrix1['deleted'] == 1){
                                                                        echo "History";
                                                                    }
                                                                    ?>
                                                                    platform
                                                                    <?=$s_matrix1['platform_id']?>

                                                                <?}?>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-4">
                                                    <div class="avatar-block mb-5">
                                                        <div class="avatar-icon <?if(!empty($item['shoulder2'])){?>empty-mat<?}?>">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0">
                                                                <?if(!empty($item['shoulder2'])){
                                                                    $s_matrix2 = \common\models\MatrixStart::findOne($item['shoulder2']);
                                                                    $shoulder2 = \common\models\User::findOne($s_matrix2['user_id']);
                                                                    ?>
                                                                    <a href="/users/view?id=<?=$shoulder2['id']?>">
                                                                        <?
                                                                        echo $shoulder2['username'];
                                                                        ?>
                                                                    </a>
                                                                    <?

                                                                    if($s_matrix2['reinvest'] == 1){
                                                                        echo "REINVEST";
                                                                    }
                                                                    if($s_matrix2['deleted'] == 1){
                                                                        echo "History";
                                                                    }
                                                                    ?>
                                                                    platform
                                                                    <?=$s_matrix2['platform_id']?>
                                                                <?}?>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">

                                                <div class="col-3">
                                                    <div class="avatar-icon <?if(!empty($item['shoulder1_1'])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0">
                                                            <?if(!empty($item['shoulder1_1'])){
                                                                $s_matrix1_1 = \common\models\MatrixStart::findOne($item['shoulder1_1']);
                                                                $shoulder1_1 = \common\models\User::findOne($s_matrix1_1['user_id']);
                                                                ?>
                                                                <a href="/users/view?id=<?=$shoulder1_1['id']?>">
                                                                    <?
                                                                    echo $shoulder1_1['username'];
                                                                    ?>
                                                                </a>
                                                                <?

                                                                if($s_matrix1_1['reinvest'] == 1){
                                                                    echo "REINVEST";
                                                                }
                                                                if($s_matrix1_1['deleted'] == 1){
                                                                    echo "History";
                                                                }
                                                                ?>
                                                                platform
                                                                <?=$s_matrix1_1['platform_id']?>
                                                            <?}?>
                                                        </h4>
                                                    </div>
                                                </div>


                                                <div class="col-3">
                                                    <div class="avatar-icon <?if(!empty($item['shoulder1_2'])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0">
                                                            <?if(!empty($item['shoulder1_2'])){
                                                                $s_matrix1_2 = \common\models\MatrixStart::findOne($item['shoulder1_2']);
                                                                $shoulder1_2 = \common\models\User::findOne($s_matrix1_2['user_id']);
                                                                ?>
                                                                <a href="/users/view?id=<?=$shoulder1_2['id']?>">
                                                                    <?
                                                                    echo $shoulder1_2['username'];
                                                                    ?>
                                                                </a>
                                                                <?

                                                                if($s_matrix1_2['reinvest'] == 1){
                                                                    echo "REINVEST";
                                                                }
                                                                if($s_matrix1_2['deleted'] == 1){
                                                                    echo "History";
                                                                }
                                                                ?>
                                                                platform
                                                                <?=$s_matrix1_2['platform_id']?><?}?></h4>
                                                    </div>
                                                </div>



                                                <div class="col-3">
                                                    <div class="avatar-icon <?if(!empty($item['shoulder2_1'])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0">
                                                            <?if(!empty($item['shoulder2_1'])){
                                                                $s_matrix2_1 = \common\models\MatrixStart::findOne($item['shoulder2_1']);
                                                                $shoulder2_1 = \common\models\User::findOne($s_matrix2_1['user_id']);
                                                                ?>
                                                                <a href="/users/view?id=<?=$shoulder2_1['id']?>">
                                                                    <?
                                                                    echo $shoulder2_1['username'];
                                                                    ?>
                                                                </a>
                                                                <?

                                                                if($s_matrix2_1['reinvest'] == 1){
                                                                    echo "REINVEST";
                                                                }
                                                                if($s_matrix2_1['deleted'] == 1){
                                                                    echo "History";
                                                                }
                                                                ?>
                                                                platform
                                                                <?=$s_matrix2_1['platform_id']?><?}?></h4>
                                                    </div>
                                                </div>


                                                <div class="col-3">
                                                    <div class="avatar-icon <?if(!empty($item['shoulder2_2'])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0">
                                                            <?if(!empty($item['shoulder2_2'])){
                                                                $s_matrix2_2 = \common\models\MatrixStart::findOne($item['shoulder2_2']);
                                                                $shoulder2_2 = \common\models\User::findOne($s_matrix2_2['user_id']);
                                                                ?>
                                                                <a href="/users/view?id=<?=$shoulder2_2['id']?>">
                                                                    <?
                                                                    echo $shoulder2_2['username'];
                                                                    ?>
                                                                </a>
                                                                <?

                                                                if($s_matrix2_2['reinvest'] == 1){
                                                                    echo "REINVEST";
                                                                }
                                                                if($s_matrix2_2['deleted'] == 1){
                                                                    echo "History";
                                                                }
                                                                ?>
                                                                platform
                                                                <?=$s_matrix2_2['platform_id']?><?}?></h4>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-12" style="border-top: 1px solid"></div>
                                    <?}

                                }?>
                            </div>
                        </div>
                    </div>
                <?} ?>
                <? if($user['newmatrix'] == 1){?>
                    <div id="matper" class="tab-pane fade">
                        <h3>Personal</h3>
                        <div class="structure-wrap">
                            <div class="row">
                                <? if(!empty($matpersonal)){
                                        foreach ($matpersonal as $item) {
                                            ?>
                                            <?if(!empty($item['parent_id'])){?>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-4 offset-4">
                                                            <div class="avatar-block mb-5">
                                                                <div class="avatar-icon">
                                                                    <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                    </svg>
                                                                </div>

                                                                <div class="avatar-block-wrap">
                                                                    <h4 class="h4 mb-0">


                                                                        <? $s_matrix_p = \common\models\MatrixRef::findOne($item['parent_id']);
                                                                        $par = \common\models\User::findOne($s_matrix_p['user_id']);
                                                                        ?>
                                                                        <a href="/users/view?id=<?=$par['id']?>">
                                                                            <?
                                                                            echo $par['username'];
                                                                            ?>
                                                                        </a>
                                                                        <?

                                                                        if($s_matrix_p['reinvest'] == 1){
                                                                            echo "REINVEST";
                                                                        }
                                                                        if($s_matrix_p['deleted'] == 1){
                                                                            echo "History";
                                                                        }
                                                                        ?>
                                                                        platform
                                                                        <?=$s_matrix_p['platform_id']?>


                                                                    </h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?}?>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-4 offset-4">
                                                        <div class="avatar-block mb-5">
                                                            <div class="avatar-icon">
                                                                <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?=$user['username']?> <?if($item['reinvest'] == 1){echo "REINVEST";}?> platform <?=$item['platform_id']?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">

                                                    <div class="col-4 offset-2">
                                                        <div class="avatar-block mb-5">
                                                            <div class="avatar-icon <?if(!empty($item['shoulder1'])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0">
                                                                    <?if(!empty($item['shoulder1'])){
                                                                        $s_matrix1 = \common\models\MatrixRef::findOne($item['shoulder1']);
                                                                        $shoulder1 = \common\models\User::findOne($s_matrix1['user_id']);
                                                                        ?>
                                                                        <a href="/users/view?id=<?=$shoulder1['id']?>">
                                                                            <?
                                                                            echo $shoulder1['username'];
                                                                            ?>
                                                                        </a>
                                                                        <?

                                                                        if($s_matrix1['reinvest'] == 1){
                                                                            echo "REINVEST";
                                                                        }
                                                                        if($s_matrix1['deleted'] == 1){
                                                                            echo "History";
                                                                        }
                                                                        ?>
                                                                        platform
                                                                        <?=$s_matrix1['platform_id']?>

                                                                    <?}?>
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-4">
                                                        <div class="avatar-block mb-5">
                                                            <div class="avatar-icon <?if(!empty($item['shoulder2'])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0">
                                                                    <?if(!empty($item['shoulder2'])){
                                                                        $s_matrix2 = \common\models\MatrixRef::findOne($item['shoulder2']);
                                                                        $shoulder2 = \common\models\User::findOne($s_matrix2['user_id']);
                                                                        ?>
                                                                        <a href="/users/view?id=<?=$shoulder2['id']?>">
                                                                            <?
                                                                            echo $shoulder2['username'];
                                                                            ?>
                                                                        </a>
                                                                        <?

                                                                        if($s_matrix2['reinvest'] == 1){
                                                                            echo "REINVEST";
                                                                        }
                                                                        if($s_matrix2['deleted'] == 1){
                                                                            echo "History";
                                                                        }
                                                                        ?>
                                                                        platform
                                                                        <?=$s_matrix2['platform_id']?>
                                                                    <?}?>
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">

                                                    <div class="col-3">
                                                        <div class="avatar-icon <?if(!empty($item['shoulder1_1'])){?>empty-mat<?}?>">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0">
                                                                <?if(!empty($item['shoulder1_1'])){
                                                                    $s_matrix1_1 = \common\models\MatrixRef::findOne($item['shoulder1_1']);
                                                                    $shoulder1_1 = \common\models\User::findOne($s_matrix1_1['user_id']);
                                                                    ?>
                                                                    <a href="/users/view?id=<?=$shoulder1_1['id']?>">
                                                                        <?
                                                                        echo $shoulder1_1['username'];
                                                                        ?>
                                                                    </a>
                                                                    <?

                                                                    if($s_matrix1_1['reinvest'] == 1){
                                                                        echo "REINVEST";
                                                                    }
                                                                    if($s_matrix1_1['deleted'] == 1){
                                                                        echo "History";
                                                                    }
                                                                    ?>
                                                                    platform
                                                                    <?=$s_matrix1_1['platform_id']?>
                                                                <?}?>
                                                            </h4>
                                                        </div>
                                                    </div>


                                                    <div class="col-3">
                                                        <div class="avatar-icon <?if(!empty($item['shoulder1_2'])){?>empty-mat<?}?>">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0">
                                                                <?if(!empty($item['shoulder1_2'])){
                                                                    $s_matrix1_2 = \common\models\MatrixRef::findOne($item['shoulder1_2']);
                                                                    $shoulder1_2 = \common\models\User::findOne($s_matrix1_2['user_id']);
                                                                    ?>
                                                                    <a href="/users/view?id=<?=$shoulder1_2['id']?>">
                                                                        <?
                                                                        echo $shoulder1_2['username'];
                                                                        ?>
                                                                    </a>
                                                                    <?

                                                                    if($s_matrix1_2['reinvest'] == 1){
                                                                        echo "REINVEST";
                                                                    }
                                                                    if($s_matrix1_2['deleted'] == 1){
                                                                        echo "History";
                                                                    }
                                                                    ?>
                                                                    platform
                                                                    <?=$s_matrix1_2['platform_id']?><?}?></h4>
                                                        </div>
                                                    </div>



                                                    <div class="col-3">
                                                        <div class="avatar-icon <?if(!empty($item['shoulder2_1'])){?>empty-mat<?}?>">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0">
                                                                <?if(!empty($item['shoulder2_1'])){
                                                                    $s_matrix2_1 = \common\models\MatrixRef::findOne($item['shoulder2_1']);
                                                                    $shoulder2_1 = \common\models\User::findOne($s_matrix2_1['user_id']);
                                                                    ?>
                                                                    <a href="/users/view?id=<?=$shoulder2_1['id']?>">
                                                                        <?
                                                                        echo $shoulder2_1['username'];
                                                                        ?>
                                                                    </a>
                                                                    <?

                                                                    if($s_matrix2_1['reinvest'] == 1){
                                                                        echo "REINVEST";
                                                                    }
                                                                    if($s_matrix2_1['deleted'] == 1){
                                                                        echo "History";
                                                                    }
                                                                    ?>
                                                                    platform
                                                                    <?=$s_matrix2_1['platform_id']?><?}?></h4>
                                                        </div>
                                                    </div>


                                                    <div class="col-3">
                                                        <div class="avatar-icon <?if(!empty($item['shoulder2_2'])){?>empty-mat<?}?>">
                                                            <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="avatar-block-wrap">
                                                            <h4 class="h4 mb-0">
                                                                <?if(!empty($item['shoulder2_2'])){
                                                                    $s_matrix2_2 = \common\models\MatrixRef::findOne($item['shoulder2_2']);
                                                                    $shoulder2_2 = \common\models\User::findOne($s_matrix2_2['user_id']);
                                                                    ?>
                                                                    <a href="/users/view?id=<?=$shoulder2_2['id']?>">
                                                                        <?
                                                                        echo $shoulder2_2['username'];
                                                                        ?>
                                                                    </a>
                                                                    <?

                                                                    if($s_matrix2_2['reinvest'] == 1){
                                                                        echo "REINVEST";
                                                                    }
                                                                    if($s_matrix2_2['deleted'] == 1){
                                                                        echo "History";
                                                                    }
                                                                    ?>
                                                                    platform
                                                                    <?=$s_matrix2_2['platform_id']?><?}?></h4>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="col-12" style="border-top: 1px solid"></div>
                                        <?}

                                    }?>
                            </div>
                        </div>
                    </div>
                <?} ?>
                <? if($user['global'] == 1){?>
                    <div id="matglob" class="tab-pane fade">
                        <h3>Global</h3>
                        <div class="structure-wrap">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4 offset-4">
                                            <div class="avatar-block mb-5">
                                                <div class="avatar-icon">
                                                    <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <h4 class="h4 mb-0"><?=$user['username']?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <? if($st == true){?>
                                    <div class="col-12">
                                        <div class="row">

                                            <div class="col-4 offset-2">
                                                <div class="avatar-block mb-5">
                                                    <div class="avatar-icon <?if(!isset($children1[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children1[0])){?><?=\common\models\User::findOne($children1[0]['user_id'])['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-4">
                                                <div class="avatar-block mb-5">
                                                    <div class="avatar-icon <?if(!isset($children1[1])){?>empty-mat<?}?>" >
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children1[1])){?><?=\common\models\User::findOne($children1[1]['user_id'])['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <?
                                            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                                                $children2 = \common\models\UserPlatforms::find()->where(['parent_id'=>$children1[0]['id']])->all();
                                            }
                                            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                                                $children3 = \common\models\UserPlatforms::find()->where(['parent_id'=>$children1[1]['id']])->all();
                                            }

                                            ?>

                                            <div class="col-3">
                                                <div class="avatar-block mb-5">
                                                    <div class="avatar-icon <?if(!isset($children2[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children2[0])){?><?=\common\models\User::findOne($children2[0]['user_id'])['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-3">
                                                <div class="avatar-block mb-5">
                                                    <div class="avatar-icon <?if(!isset($children2[1])){?>empty-mat<?}?>" >
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children2[1])){?><?=\common\models\User::findOne($children2[1]['user_id'])['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-3">
                                                <div class="avatar-block mb-5">
                                                    <div class="avatar-icon <?if(!isset($children3[0])){?>empty-mat<?}?>" >
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3[0])){?><?=\common\models\User::findOne($children3[0]['user_id'])['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-3">
                                                <div class="avatar-block mb-5">
                                                    <div class="avatar-icon <?if(!isset($children3[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="3.5em" height="3.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3[1])){?><?=\common\models\User::findOne($children3[1]['user_id'])['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                <?} ?>

                            </div>
                        </div>
                    </div>
                <?} ?>
            </div>
        </div>
        <div id="panel1" class="tab-pane fade">
            <h3>Бинар</h3>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <a data-sort="title">Программа</a>
                    </th>
                    <th>
                        <a  data-sort="email">Уровень</a>
                    </th>
                    <th>
                        <a  data-sort="tel">Статус</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?
                $i = 0;
                foreach ($binars as $binar) {
                    $i++;
                    ?>
                    <tr data-key="36">
                        <td><?=$i?></td>
                        <td><?=\common\models\Promotion::findOne($binar['pr_id'])['title']?></td>
                        <td>
                            <?=\common\models\BonusTarifs::findOne($binar['tarif_id'])['title']?>
                        </td>
                        <td>
                            <?
                                if($binar['status'] == 3){
                                    echo "В процессе";
                                }elseif ($binar['status']==1){
                                    echo "Выплачена";
                                }
                            ?>
                        </td>
                    </tr>
                <?}?>
                </tbody>
            </table>
        </div>
        <div id="panel5" class="tab-pane fade">
            <h3>Доступы к курсам</h3>
            <table class="table table-striped table-bordered"><thead>
                <tr>
                    <th>#</th>
                    <th>
                        <a href="/users/view?id=58&amp;sort=title" data-sort="title">Курс</a>
                    </th>
                    <th>
                        <a href="/users/view?id=58&amp;sort=email" data-sort="email">Доступ</a>
                    </th>
                    <th>
                        <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Открыть доступ</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?
                $i = 0;
                foreach ($courses as $cours) {
                    $i++;
                    ?>
                    <tr data-key="36">
                        <td><?=$i?></td>
                        <td><?=$cours['title']?></td>
                        <td>
                            <?
                            $user_course = \common\models\UserCourses::find()->where(['user_id'=>$model->id,'course_id'=>$cours['id']])->one();
                            if(!empty($user_course)){
                                $access = 1;
                                echo "<span class='btn-success badge'>Открыт</span>";
                            }else{
                                $access = null;
                                echo "<span class='btn-danger badge'>Закрыт</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <?
                            if($access == null){?>
                                <a class="btn" href="/users/access?user_id=<?=$model['id']?>&course_id=<?=$cours['id']?>">Открыть доступ</a>
                            <?}elseif($access == 1){?>
                                <a class="btn" href="/users/access?user_id=<?=$model['id']?>&course_id=<?=$cours['id']?>">Закрыть доступ</a>
                            <?}?>
                        </td>
                    </tr>
                <?}?>
                </tbody>
            </table>
        </div>

    </div>
</div>
