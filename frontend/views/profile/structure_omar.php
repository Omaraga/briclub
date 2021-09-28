<?php

/* @var $this yii\web\View */
/* @var $structureView \frontend\models\StructureView */

use common\models\Canplatforms;
use common\models\Matblocks;
use common\models\MatrixRef;
use common\models\MLevelsNew;
use common\models\Premiums;
use common\models\Tokens;
$url = '/profile/structure';
$this->title = "Матрица";
$mat_param = Yii::$app->getRequest()->getQueryParam('m');

if(!Yii::$app->user->isGuest){
    $main_user = \common\models\User::findOne($user_id);
    $user = \common\models\User::findOne($user_id);
}
if(!empty($c)){
    $mat = \common\models\MatrixRef::findOne($c);
    $m = $mat['platform_id'];
    $user = \common\models\User::findOne($mat['user_id']);
    $main_id = MatrixRef::find()->where(['user_id'=>$main_user['id'],'platform_id'=>$m])->orderBy('id asc')->one()['id'];
    $need_id = $main_id;
}else{
    $mat = MatrixRef::find()->where(['user_id'=>$main_user['id'],'platform_id'=>$m])->orderBy('id asc')->one();
    $need_id = $mat['id'];
}
$user_tokens = Tokens::findOne(['user_id'=>$user['id']]);
$tokens = 0;
if(!empty($user_tokens)){
    $tokens = $user_tokens->balans;
}
$last = \common\models\MatrixRef::getLastChild($need_id,$m);

$shoulder = 0;
if(!empty($last)){
    if($last['children'] == 0){
        $shoulder = 1;
    }else{
        $shoulder = 2;
    }
}

$vacant = 1;

$item = $mat;

$cans = Canplatforms::find()->where(['user_id'=>$main_user['id'],'platform'=>$m+1])->all();
$main_id = MatrixRef::find()->where(['user_id'=>$main_user['id'],'platform_id'=>$m])->orderBy('id asc')->one()['id'];
$mat_count = \common\models\MatrixRef::find()->where(['user_id'=>$main_user['id'],'platform_id'=>$m,'reinvest'=>1])->orderBy('platform_id desc')->all();
$mat_count_orig = \common\models\MatrixRef::find()->where(['user_id'=>$main_user['id'],'platform_id'=>$m,'reinvest'=>0])->orderBy('platform_id desc')->all();
$price = MLevelsNew::findOne($m)['price'];
$lastmat = MatrixRef::find()->where(['platform_id'=>$m-1,'user_id'=>$main_user['id']])->one();
if($m == 1){
    $lastmat = 1;
}
$script = <<< JS
    $(".matrixes").change(function(){
        mat = $(this).val();
        window.location.href = "$url?m="+mat;

    });
    $(".clones").change(function(){
        mat = $(this).val();
        window.location.href = "$url?c="+mat;

    });
JS;
$this->registerJs($script);


$m_num = 2;
$premium = Premiums::findOne(['user_id' => Yii::$app->user->id]);
if($premium != null && $premium->is_active == 1){
    $m_num = 1;
}
?>
    <style>
        .desh{
            display: block!important;
        }
        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 999999;
            top: 0;
            left: 0;
            background-color: #fff;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            color: #828282;
        }


        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }
        .structure-matrix .desh .parent-link {
            position: absolute;
            top: -2.5rem;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-radius: 10%;
            font-weight: 700;
            color: #fff;
            padding: 0 5px;
            width: auto;
        }
        .structure-matrix .desh .main-link {
            position: absolute;
            bottom: -2.5rem;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-radius: 10%;
            font-weight: 700;
            color: #fff;
            padding: 0 5px;
            width: auto;
        }
        .breadcrumb-item+.breadcrumb-item::before {
            display: inline-block;
            padding-right: .5rem;
            padding-left: .5rem;
            color: #6c757d;
            content: "/";
        }
        .breadcrumb {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            padding: .75rem 1rem;
            margin-bottom: 1rem;
            list-style: none;
            background-color: #fff;
            border-radius: .25rem;
        }
        .block{
            position: absolute;
            padding: 0 .25rem;
            height: 2rem;
            top: 1rem;
            color: red;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-radius: .25rem;
            font-weight: 700;
            z-index: 999;
        }
        .unblock{
            position: absolute;
            padding: 0 .25rem;
            height: 2rem;
            top: 1rem;
            color: green;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-radius: .25rem;
            font-weight: 700;
            z-index: 999;
        }
        .borderred{
            border: 2px solid #df1616;
            background-color: #ccc!important;
        }
        body{
            background-color: #ECECFF !important;
        }
        .his.active {
            background: gray!important;
            color: #fff!important;
        }
        .structure-matrix .wrap-matrix {
            width: 40rem;
        }
        .structure-matrix .desh .item .icon {
            width: 7rem;
            height: 8rem;
            border-radius: 10%;
            background: rgba(130, 193, 255, 0.25);
        }
        .structure-matrix .desh .number {
            position: absolute;
            background: #02A651;
            top: -1rem;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-radius: 10%;
            font-weight: 700;
            color: #fff;
            padding: 0 5px;
            width: auto;
        }

        .structure-matrix .h5 {
            color: #000;
        }
        .d-block{
            padding: 5px;
            border-radius: 4px;
            background-color: #1989F8;
            color: #fff;
            text-align: center;
            font-size: 20px;
            cursor:pointer;
            margin-top: 20px;
        }

        .wrap-matrix{
            background: #071B43;
            border-radius: 32px;
        }

        body{
            background-color: #fff !important;
        }
    </style>
<?
$flashes = Yii::$app->session->allFlashes;
if(!empty($flashes)){
    foreach ($flashes as $key => $flash) {?>
        <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert" style="padding-left: 10%;">
            <?=$flash?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?}}
?>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="p-5">
            <h5 class="h5 mb-0 mr-4">Поиск по структуре</h5>
            <form action="/profile/structure" class="form-inline">
                <div class="form-group row">
                    <div class="col-8">
                        <input value="<?=$username?>" name="username" type="text" class="form-control" placeholder="Введите логин">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-success ml-1">Поиск</button>
                    </div>
                </div>
            </form>
            <h5 class="h5 mb-0 mr-4 pl-0 col-12 mt-2">Матрица</h5>
            <div class="form-group">
                <select name="mats" id="matrixes" class="form-control matrixes">
                    <option value="1" <? if($m == 1){echo "selected";} ?>>Матрица 1</option>
                    <option value="2" <? if($m == 2){echo "selected";} ?>>Матрица 2</option>
                    <option value="3" <? if($m == 3){echo "selected";} ?>>Матрица 3</option>
                    <option value="4" <? if($m == 4){echo "selected";} ?>>Матрица 4</option>
                    <option value="5" <? if($m == 5){echo "selected";} ?>>Матрица 5</option>
                    <option value="6" <? if($m == 6){echo "selected";} ?>>Матрица 6</option>
                </select>
            </div>

            <h5 class="h5 mb-0 mr-4 pl-0 col-12">Клоны (<?=count($mat_count)?>)</h5>
            <div class="form-group">
                <select name="" id="clones" class="clones form-control">
                    <option value="0">Выберите клона</option>
                    <? foreach ($mat_count as $item_m) {?>
                        <option <?if($c==$item_m['id']){echo "selected";}?> value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                    <?}?>
                </select>
            </div>
            <h5 class="h5 mb-0 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig)?>)</h5>
            <div class="form-group">
                <select name="" id="clones" class="clones form-control">
                    <option value="0">Выберите место</option>
                    <? foreach ($mat_count_orig as $item_o) {?>
                        <option <?if($c==$item_o['id']){echo "selected";}?> value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                    <?}?>
                </select>
            </div>
            <?if(!empty($cans)){?>
                <div class="alert alert-success" role="alert">
                    <h5 class="alert-heading">Заполненные места (<?=count($cans)?>)</h5>
                    <p>Вы можете активировать места в матрице <?=($m+1)?> бесплатно:</p>
                    <? foreach ($cans as $can) {?>

                        <a style="font-size: 1rem!important;float: left;color: #1989F8;" href="/profile/structure?c=<?=$can['mat_id']?>">[<?=$can['mat_id']?>] </a> <a style="color: #1989F8;font-size: 1rem!important;" href="#" data-toggle="modal" data-target="#confirmModal2" type="submit" class="btn btn-link  ml-0">Активировать место</a>

                    <?}?>
                </div>

            <?}?>
            <div class="form-group">

                <?
                $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();
                if(!empty($canp)){?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Поздравляем!</h4>
                        <p>Вы закрыли матрицу <?=$m-1?>, теперь можете активировать матрицу <?=$m?> бесплатно</p>
                    </div>
                <?}else{?>
                    <a href="#" data-toggle="modal" data-target="#confirmModal" style="font-size: 1rem!important;" type="submit" class="btn btn-success  ml-0">Купить место $<?=$price?></a>
                <?}?>

            </div>
            <?if(!empty($mat)){?>
                <div class="form-group">
                    <a href="<?=$url?>?c=<?=$last['id']?>&shoulder=<?=$shoulder?>" style="font-size: 1rem!important;" type="submit" class="btn btn-success  ml-0">Следующее место</a>
                </div>
            <?}?>

        </div>
    </div>
    <main class="structure-matrix">
        <div class="container">
            <div class="row">
                <div class="col-5 d-none d-md-block">
                    <div class="form-search d-flex mt-5 flex-wrap">
                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">

                            <li class="nav-item " role="presentation">
                                <a class="active" role="tab" style="color: #474747;font-weight: 500;font-size: 18px;font-family: 'HelveticaMedium';" aria-selected="true">Матрица <?=$m?></a>
                            </li>

                        </ul>
                        <form action="/profile/structure" class="form-inline">
                            <h5 class="h5 mb-2 mt-2 mr-4">Поиск по структуре</h5>
                            <div class="form-group">
                                <input value="<?=$username?>" name="username" type="text" class="form-control" placeholder="Введите логин">
                                <button type="submit" class="btn btn-success ml-1">Поиск</button>
                            </div>
                        </form>
                        <h5 class="h5 mb-2 mt-2 mr-4 pl-0 col-12 mt-2">Матрица</h5>
                        <div class="form-group">
                            <select name="mats" id="matrixes" class="matrixes form-control">
                                <option value="1" <? if($m == 1){echo "selected";} ?>>Матрица 1</option>
                                <option value="2" <? if($m == 2){echo "selected";} ?>>Матрица 2</option>
                                <option value="3" <? if($m == 3){echo "selected";} ?>>Матрица 3</option>
                                <option value="4" <? if($m == 4){echo "selected";} ?>>Матрица 4</option>
                                <option value="5" <? if($m == 5){echo "selected";} ?>>Матрица 5</option>
                                <option value="6" <? if($m == 6){echo "selected";} ?>>Матрица 6</option>
                            </select>
                        </div>
                        <h5 class="h5 mb-2 mt-2 mr-4 pl-0 col-12">Клоны (<?=count($mat_count)?>)</h5>
                        <div class="form-group">
                            <select name="" id="clones" class="clones form-control">
                                <option value="0">Выберите клона</option>
                                <? foreach ($mat_count as $item_m) {?>
                                    <option <?if($c==$item_m['id']){echo "selected";}?> value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                                <?}?>
                            </select>
                        </div>
                        <h5 class="h5 mb-2 mt-2 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig)?>)</h5>
                        <div class="form-group">
                            <select name="" id="clones" class="clones form-control">
                                <option value="0">Выберите место</option>
                                <? foreach ($mat_count_orig as $item_o) {?>
                                    <option <?if($c==$item_o['id']){echo "selected";}?> value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                                <?}?>
                            </select>
                        </div>
                        <?if(!empty($cans)){?>
                            <div class="alert alert-success" role="alert">
                                <h5 class="alert-heading">Заполненные места (<?=count($cans)?>)</h5>
                                <p>Вы можете активировать места в матрице <?=($m+1)?> бесплатно:</p>
                                <? foreach ($cans as $can) {?>

                                    <a  href="/profile/structure?c=<?=$can['mat_id']?>">[<?=$can['mat_id']?>] </a> <a href="#" data-toggle="modal" data-target="#confirmModal2" type="submit" class="btn btn-link  ml-0">Активировать место</a>

                                <?}?>
                            </div>

                        <?}?>


                        <div class="form-group col-6 pl-0">

                            <?
                            $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();
                            if(!empty($canp)){?>
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Поздравляем!</h4>
                                    <p>Вы закрыли матрицу <?=$m-1?>, теперь можете активировать матрицу <?=$m?> бесплатно</p>
                                </div>
                            <?}else{

                                if(!empty($lastmat)){?>
                                    <a href="#" data-toggle="modal" data-target="#confirmModal" type="submit" class="btn btn-success  ml-0">Купить место $<?=$price?></a>

                                <?}
                                ?>

                            <?}?>

                        </div>
                        <?if(!empty($mat)){?>
                            <div class="form-group">
                                <a href="<?=$url?>?c=<?=$last['id']?>&shoulder=<?=$shoulder?>" type="submit" class="btn btn-success  ml-0" style="background: transparent; color: #02A651">Следующее место</a>
                            </div>
                        <?}?>

                    </div>
                </div>
                <div class="col-7">

                    <div class="hgroup pb-2 text-center">

                    </div>



                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" >
                            <div class="desh">
                                <div class="wrap-matrix" style="padding-top: 10rem">
                                    <nav aria-label="breadcrumb" class="mt-5" style="position: absolute; top: 3.5rem; width: 90%">
                                        <ol style="background: rgba(130, 193, 255, 0.25);" class="breadcrumb">
                                            <li class="mr-3"><a style="color: #fff" href="/profile/structure?m=<?=$m?>"><?=$main_user['username']?>:</a>  </li>


                                            <?
                                            if(!empty($c)){
                                                $parents = $structureView->parents;

                                                foreach ($parents as $parent) {?>
                                                    <li class="breadcrumb-item"><a style="color: #fff" href="/profile/structure?c=<?=$parent['parent_id']?>"><?=\common\models\User::findOne(\common\models\MatrixRef::findOne($parent['parent_id'])['user_id'])['username']?>[<?=$parent['parent_id']?>]</a></li>
                                                <?}
                                            }
                                            ?>

                                            <li class="breadcrumb-item active" style="color: #fff" aria-current="page"><?=$user['username']?>[<?=$item['id']?>]</li>
                                        </ol>
                                        <span class="d-block d-md-none" onclick="openNav()">Инструменты структуры</span>
                                    </nav>
                                    <?if(!empty($structureView->matrixRef)){
                                        if($last['id'] == $structureView->matrixRef['id']){
                                            $vacant = "s-$shoulder";
                                        }
                                        ?>
                                        <div class="item mt-md-0 mt-5">
                                            <div class="modal fade" id="modal-user-<?=$structureView->matrixRef['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-user-<?=$structureView->matrixRef['id']?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                <?=$user['username']?>
                                                                <span style="color: #6e6e6e;">
                                                            <?
                                                            if ($structureView->matrixRef['reinvest'] == 1) {
                                                                echo "Clone";
                                                            }
                                                            ?>
                                                        </span>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <table class="table table-striped">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="color: #6e6e6e;">Площадка</td>
                                                                    <td><span style="float: right;"><?=$structureView->matrixRef['platform_id']?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="color: #6e6e6e;">Дата активации</td>
                                                                    <td><span style="float: right;"><?=date('d.m.Y H:i',$structureView->matrixRef['time'])?></span></td>
                                                                </tr>

                                                                <?if(!empty($structureView->matrixRef['parent_id'])){?>
                                                                    <tr>
                                                                        <td style="color: #6e6e6e;">Вышестоящее место</td>
                                                                        <td><span style="float: right;"><?=\common\models\User::findOne(\common\models\MatrixRef::findOne($structureView->matrixRef['parent_id'])['user_id'])['username']?>[<?=$structureView->matrixRef['parent_id']?>]</span></td>
                                                                    </tr>
                                                                <?}?>
                                                                <tr>
                                                                    <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                    <td><span style="float: right;"><?=$structureView->refSize?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="color: #6e6e6e;">Личники</td>
                                                                    <td><span style="float: right;"><?=$structureView->ownRefsSize?></span></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="icon <?if($structureView->matrixRef['deleted'] == 1){?>empty-mat<?}?> <?if(!empty($structureView->matrixBlock)){?>borderred<?}?>" style="background: rgba(130, 193, 255, 0.25);">
                                                <?if(!empty($structureView->matrixRef['parent_id']) and $structureView->matrixRef['id'] != $main_id){?>
                                                    <div class="parent-link"><a style="color: #000" href="/profile/structure?c=<?=$structureView->matrixRef['parent_id']?>">^ ***</a></div>
                                                <?}?>

                                                <div class="number"><? echo sprintf("%'06d\n", $structureView->matrixRef['id']);?></div>
                                                <?
                                                if($structureView->matrixRef['id'] > $m_num) {
                                                    if (!empty($structureView->matrixBlock)) {
                                                        ?>
                                                        <a href="/profile/unblock?id=<?= $structureView->matrixRef['id'] ?>"
                                                           class="small unblock"> Разблокировать</a>
                                                        <?
                                                    } else {
                                                        ?>
                                                        <a href="/profile/block?id=<?= $structureView->matrixRef['id'] ?>"
                                                           class="small block"> Заблокировать</a>
                                                        <?
                                                    }
                                                }?>

                                                <? if ($structureView->matrixRef['reinvest'] == 1) {?>
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                        <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                    </svg>
                                                <?}else{?>
                                                    <img style="width: 1rem;" src="/img/dollar.svg" alt="">
                                                <?}?>

                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-user-<?=$structureView->matrixRef['id']?>"></a>
                                                <?
                                                $num = \common\models\MatClons::find()->where(['mat_id'=>$structureView->matrixRef['id']])->one();
                                                ?>
                                                <div class="name" style="font-weight: normal">$ <?=$user['username']?><?if(!empty($num)){?>(<?=$num['num']?>)<?}?></div>
                                                <div class="main-link"><a style="color: #fff" href="/profile/structure?c=<?=$structureView->matrixRef['id']?>">***</a></div>
                                            </div>

                                        </div>
                                    <?}else{?>
                                        <div class="item">
                                            <div class="icon empty-mat">
                                                <?if($vacant==1){?>
                                                    <?
                                                    $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();

                                                    if(!empty($lastmat)){?>
                                                        <a style="height: 130px;width: 130px; border-radius: 10%; display: flex; align-items: center; background: #33B673" href="#" data-toggle="modal" data-target="#confirmModal" type="submit" class="btn btn-success  ml-0"><?if(!empty($canp)){echo "Активировать место";}else{echo "Купить место $$price";}?></a>

                                                    <?}
                                                    ?>
                                                <?}else{?>
                                                    <a href="#" class="user stretched-link" ></a>
                                                <?}?>
                                            </div>

                                        </div>
                                    <?}?>
                                    <div class="shoulder">
                                        <div class="left">
                                            <div class="item mb-5">
                                                <?
                                                $empty = true;
                                                if(!empty($item['shoulder1'])){
                                                    $s_matrix1 = \common\models\MatrixRef::findOne($item['shoulder1']);
                                                    if(!empty($s_matrix1)){
                                                        $empty = false;
                                                        if($last['id'] == $s_matrix1['id']){
                                                            $vacant = "s-1_$shoulder";
                                                        }
                                                    }
                                                }
                                                ?>
                                                <?
                                                if(!empty($item['shoulder1'])){

                                                    ?>
                                                    <?
                                                    $refmat1 = \common\models\MatParents::find()->where(['parent_id'=>$s_matrix1['id']])->count();
                                                    $shoulder1 = \common\models\User::findOne($s_matrix1['user_id']);
                                                    $refmat_own1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1['id'],'level'=>1,'activ'=>1])->count();
                                                    $mat_count1 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1['id'],'platform_id'=>$m,'reinvest'=>1])->orderBy('platform_id desc')->all();
                                                    $mat_count_orig1 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1['id'],'platform_id'=>$m,'reinvest'=>0])->orderBy('platform_id desc')->all();
                                                    ?>
                                                    <div class="modal fade" id="modal-ref-<?=$s_matrix1['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-<?=$s_matrix1['id']?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        <?
                                                                        echo $shoulder1['username'];
                                                                        ?>
                                                                        <span style="color: #6e6e6e;">
                                                                        <?
                                                                        if ($s_matrix1['reinvest'] == 1) {
                                                                            echo "Clone";
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table table-striped">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td style="color: #6e6e6e;">ФИО</td>
                                                                            <td><span style="float: right;"><?=$shoulder1['fio']?></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="color: #6e6e6e;">Телефон</td>
                                                                            <td><span style="float: right;"><?=$shoulder1['phone']?></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="color: #6e6e6e;">Площадка</td>
                                                                            <td><span style="float: right;"><?=$item['platform_id']?></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="color: #6e6e6e;">Дата активации</td>
                                                                            <td><span style="float: right;"><?=date('d.m.Y H:i',$s_matrix1['time'])?></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="color: #6e6e6e;">Спонсор</td>
                                                                            <td><span style="float: right;"><?=\common\models\User::findOne($shoulder1['parent_id'])['username']?></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                            <td><span style="float: right;"><?=$refmat1?></span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="color: #6e6e6e;">Личники</td>
                                                                            <td><span style="float: right;"><?=$refmat_own1?></span></td>
                                                                        </tr>
                                                                        </tbody>

                                                                    </table>
                                                                    <div>
                                                                        <p class="mb-0 mr-4 pl-0 col-12">Клоны (<?=count($mat_count1)?>)</p>
                                                                        <div class="form-group">
                                                                            <select name="" id="clones" class="clones form-control">
                                                                                <option value="0">Выберите клона</option>
                                                                                <? foreach ($mat_count1 as $item_m) {?>
                                                                                    <option value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                                                                                <?}?>
                                                                            </select>
                                                                        </div>
                                                                        <p class="mb-0 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig1)?>)</p>
                                                                        <div class="form-group">
                                                                            <select name="" id="clones" class="clones form-control">
                                                                                <option value="0">Выберите место</option>
                                                                                <? foreach ($mat_count_orig1 as $item_o) {?>
                                                                                    <option value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                                                                                <?}?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <? $block1 = Matblocks::find()->where(['user_id'=>$main_user['id'],'mat_id'=>$s_matrix1['id']])->one();?>
                                                    <div class="icon <?if($s_matrix1['deleted'] == 1 or $empty){?>empty-mat<?}?> <?if(!empty($block1) or $empty){?>borderred<?}?>" >
                                                        <div class="number"><? echo sprintf("%'06d\n", $s_matrix1['id']);?></div>
                                                        <?

                                                        if ($m > $m_num) {
                                                            if (!empty($block1)) {
                                                                ?>
                                                                <a href="/profile/unblock?id=<?= $s_matrix1['id'] ?>"
                                                                   class="small unblock"> Разблокировать</a>
                                                                <?
                                                            } else {
                                                                ?>
                                                                <a href="/profile/block?id=<?= $s_matrix1['id'] ?>"
                                                                   class="small block"> Заблокировать</a>
                                                                <?
                                                            }
                                                        }?>
                                                        <? if ($s_matrix1['reinvest'] == 1) {?>
                                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                                <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                            </svg>
                                                        <?}else{?>
                                                            <img style="width: 1rem;" src="/img/dollar.svg" alt="">
                                                        <?}?>
                                                        <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix1['id']?>"></a>
                                                        <?
                                                        $num1 = \common\models\MatClons::find()->where(['mat_id'=>$s_matrix1['id']])->one();
                                                        ?>
                                                        <div class="name"><? echo $shoulder1['username'];?><?if(!empty($num1)){?>(<?=$num1['num']?>)<?}?></div>
                                                        <div class="main-link"><a style="color: #000" href="/profile/structure?c=<?=$s_matrix1['id']?>">***</a></div>
                                                    </div>

                                                <?}else{?>
                                                    <div class="icon empty-mat" >
                                                        <?if($vacant == "s-1"){?>
                                                            <?
                                                            $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();

                                                            if(!empty($lastmat)){?>
                                                                <a style="height: 130px;width: 130px; border-radius: 10%; display: flex; align-items: center; background: #33B673" href="#" data-toggle="modal" data-target="#confirmModal" type="submit" class="btn btn-success  ml-0"><?if(!empty($canp)){echo "Активировать место";}else{echo "$". $price . "\nкупить";}?></a>

                                                            <?}
                                                            ?>
                                                        <?}else{?>
                                                            <a href="#" class="user stretched-link" ></a>
                                                        <?}?>

                                                    </div>
                                                <?}
                                                ?>

                                            </div>
                                            <div class="level">
                                                <div class="item">
                                                    <?
                                                    $empty = true;
                                                    if(!empty($item['shoulder1_1'])){
                                                        $s_matrix1_1 = \common\models\MatrixRef::findOne($item['shoulder1_1']);
                                                        if(!empty($s_matrix1_1)){
                                                            $empty = false;
                                                        }
                                                    }
                                                    ?>
                                                    <?
                                                    if(!empty($item['shoulder1_1'])){?>
                                                        <?
                                                        $refmat1_1 = \common\models\MatParents::find()->where(['parent_id'=>$s_matrix1_1['id']])->count();
                                                        $shoulder1_1 = \common\models\User::findOne($s_matrix1_1['user_id']);
                                                        $refmat_own1_1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1_1['id'],'level'=>1,'activ'=>1])->count();
                                                        $mat_count1_1 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1_1['id'],'platform_id'=>$m,'reinvest'=>1])->orderBy('platform_id desc')->all();
                                                        $mat_count_orig1_1 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1_1['id'],'platform_id'=>$m,'reinvest'=>0])->orderBy('platform_id desc')->all();
                                                        ?>
                                                        <div class="modal fade" id="modal-ref-<?=$s_matrix1_1['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-<?=$s_matrix1_1['id']?>" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <?
                                                                            echo $shoulder1_1['username'];
                                                                            ?>
                                                                            <span style="color: #6e6e6e;">
                                                                        <?
                                                                        if ($s_matrix1_1['reinvest'] == 1) {
                                                                            echo "Clone";
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-striped">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">ФИО</td>
                                                                                <td><span style="float: right;"><?=$shoulder1_1['fio']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Телефон</td>
                                                                                <td><span style="float: right;"><?=$shoulder1_1['phone']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Площадка</td>
                                                                                <td><span style="float: right;"><?=$item['platform_id']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Дата активации</td>
                                                                                <td><span style="float: right;"><?=date('d.m.Y H:i',$s_matrix1_1['time'])?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Спонсор</td>
                                                                                <td><span style="float: right;"><?=\common\models\User::findOne($shoulder1_1['parent_id'])['username']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                                <td><span style="float: right;"><?=$refmat1_1?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Личники</td>
                                                                                <td><span style="float: right;"><?=$refmat_own1_1?></span></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div>
                                                                            <p class="mb-0 mr-4 pl-0 col-12">Клоны (<?=count($mat_count1_1)?>)</p>
                                                                            <div class="form-group">
                                                                                <select name="" id="clones" class="clones form-control">
                                                                                    <option value="0">Выберите клона</option>
                                                                                    <? foreach ($mat_count1_1 as $item_m) {?>
                                                                                        <option value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                                                                                    <?}?>
                                                                                </select>
                                                                            </div>
                                                                            <p class="mb-0 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig1_1)?>)</p>
                                                                            <div class="form-group">
                                                                                <select name="" id="clones" class="clones form-control">
                                                                                    <option value="0">Выберите место</option>
                                                                                    <? foreach ($mat_count_orig1_1 as $item_o) {?>
                                                                                        <option value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                                                                                    <?}?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <? $block1_1 = Matblocks::find()->where(['user_id'=>$main_user['id'],'mat_id'=>$s_matrix1_1['id']])->one();?>
                                                        <div class="icon <?if($s_matrix1_1['deleted'] == 1 or $empty){?>empty-mat<?}?> <?if(!empty($block1_1) or $empty){?>borderred<?}?>" >
                                                            <div class="number"><? echo sprintf("%'06d\n", $s_matrix1_1['id']);?></div>
                                                            <?

                                                            if ($m > $m_num) {
                                                                if (!empty($block1_1)) {
                                                                    ?>
                                                                    <a href="/profile/unblock?id=<?= $s_matrix1_1['id'] ?>"
                                                                       class="small unblock"> Разблокировать</a>
                                                                    <?
                                                                } else {
                                                                    ?>
                                                                    <a href="/profile/block?id=<?= $s_matrix1_1['id'] ?>"
                                                                       class="small block"> Заблокировать</a>
                                                                    <?
                                                                }
                                                            }?>
                                                            <? if ($s_matrix1_1['reinvest'] == 1) {?>
                                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                                    <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                                </svg>
                                                            <?}else{?>
                                                                <img style="width: 1rem;" src="/img/dollar.svg" alt="">
                                                            <?}?>
                                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix1_1['id']?>"></a>
                                                            <?
                                                            $num1_1 = \common\models\MatClons::find()->where(['mat_id'=>$s_matrix1_1['id']])->one();
                                                            ?>
                                                            <div class="name"><? echo $shoulder1_1['username'];?><?if(!empty($num1_1)){?>(<?=$num1_1['num']?>)<?}?></div>
                                                            <div class="main-link"><a style="color: #000" href="/profile/structure?c=<?=$s_matrix1_1['id']?>">***</a></div>
                                                        </div>

                                                    <?}else{?>
                                                        <div class="icon empty-mat" >
                                                            <?if($vacant == "s-1_1"){?>
                                                                <?
                                                                $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();

                                                                if(!empty($lastmat)){?>
                                                                    <a style="height: 97px;width: 130px;padding-top: 22px;" href="#" data-toggle="modal" data-target="#confirmModal" type="submit" class="btn btn-success  ml-0"><?if(!empty($canp)){echo "Активировать место";}else{echo "Купить место $$price";}?></a>

                                                                <?}
                                                                ?>
                                                            <?}else{?>
                                                                <a href="#" class="user stretched-link" ></a>
                                                            <?}?>
                                                        </div>
                                                    <?}
                                                    ?>

                                                </div>
                                                <div class="item">
                                                    <?
                                                    $empty = true;
                                                    if(!empty($item['shoulder1_2'])){
                                                        $s_matrix1_2 = \common\models\MatrixRef::findOne($item['shoulder1_2']);
                                                        if(!empty($s_matrix1_2)){
                                                            $empty = false;

                                                        }
                                                    }
                                                    ?>
                                                    <?
                                                    if(!empty($item['shoulder1_2'])){?>
                                                        <?
                                                        $refmat1_2 = \common\models\MatParents::find()->where(['parent_id'=>$s_matrix1_2['id']])->count();
                                                        $shoulder1_2 = \common\models\User::findOne($s_matrix1_2['user_id']);
                                                        $refmat_own1_2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1_2['id'],'level'=>1,'activ'=>1])->count();
                                                        $mat_count1_2 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1_2['id'],'platform_id'=>$m,'reinvest'=>1])->orderBy('platform_id desc')->all();
                                                        $mat_count_orig1_2 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1_2['id'],'platform_id'=>$m,'reinvest'=>0])->orderBy('platform_id desc')->all();
                                                        ?>
                                                        <div class="modal fade" id="modal-ref-<?=$s_matrix1_2['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-<?=$s_matrix1_2['id']?>" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <?
                                                                            echo $shoulder1_2['username'];
                                                                            ?>
                                                                            <span style="color: #6e6e6e;">
                                                                        <?
                                                                        if ($s_matrix1_2['reinvest'] == 1) {
                                                                            echo "Clone";
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-striped">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">ФИО</td>
                                                                                <td><span style="float: right;"><?=$shoulder1_2['fio']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Телефон</td>
                                                                                <td><span style="float: right;"><?=$shoulder1_2['phone']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Площадка</td>
                                                                                <td><span style="float: right;"><?=$item['platform_id']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Дата активации</td>
                                                                                <td><span style="float: right;"><?=date('d.m.Y H:i',$s_matrix1_2['time'])?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Спонсор</td>
                                                                                <td><span style="float: right;"><?=\common\models\User::findOne($shoulder1_2['parent_id'])['username']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                                <td><span style="float: right;"><?=$refmat1_2?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Личники</td>
                                                                                <td><span style="float: right;"><?=$refmat_own1_2?></span></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div>
                                                                            <p class="mb-0 mr-4 pl-0 col-12">Клоны (<?=count($mat_count1_2)?>)</p>
                                                                            <div class="form-group">
                                                                                <select name="" id="clones" class="clones form-control">
                                                                                    <option value="0">Выберите клона</option>
                                                                                    <? foreach ($mat_count1_2 as $item_m) {?>
                                                                                        <option value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                                                                                    <?}?>
                                                                                </select>
                                                                            </div>
                                                                            <p class="mb-0 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig1_2)?>)</p>
                                                                            <div class="form-group">
                                                                                <select name="" id="clones" class="clones form-control">
                                                                                    <option value="0">Выберите место</option>
                                                                                    <? foreach ($mat_count_orig1_2 as $item_o) {?>
                                                                                        <option value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                                                                                    <?}?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <? $block1_2 = Matblocks::find()->where(['user_id'=>$main_user['id'],'mat_id'=>$s_matrix1_2['id']])->one();?>
                                                        <div class="icon <?if($s_matrix1_2['deleted'] == 1 or $empty){?>empty-mat<?}?> <?if(!empty($block1_2) or $empty){?>borderred<?}?>" >
                                                            <div class="number"><? echo sprintf("%'06d\n", $s_matrix1_2['id']);?></div>
                                                            <?

                                                            if ($m > $m_num) {
                                                                if (!empty($block1_2)) {
                                                                    ?>
                                                                    <a href="/profile/unblock?id=<?= $s_matrix1_2['id'] ?>"
                                                                       class="small unblock"> Разблокировать</a>
                                                                    <?
                                                                } else {
                                                                    ?>
                                                                    <a href="/profile/block?id=<?= $s_matrix1_2['id'] ?>"
                                                                       class="small block"> Заблокировать</a>
                                                                    <?
                                                                }
                                                            }?>
                                                            <? if ($s_matrix1_2['reinvest'] == 1) {?>
                                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                                    <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                                </svg>
                                                            <?}else{?>
                                                                <img style="width: 1rem;" src="/img/dollar.svg" alt="">
                                                            <?}?>
                                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix1_2['id']?>"></a>
                                                            <?
                                                            $num1_2 = \common\models\MatClons::find()->where(['mat_id'=>$s_matrix1_2['id']])->one();
                                                            ?>
                                                            <div class="name"><? echo $shoulder1_2['username'];?><?if(!empty($num1_2)){?>(<?=$num1_2['num']?>)<?}?></div>
                                                            <div class="main-link"><a style="color: #000" href="/profile/structure?c=<?=$s_matrix1_2['id']?>">***</a></div>
                                                        </div>

                                                    <?}else{?>
                                                        <div class="icon empty-mat" >
                                                            <?if($vacant == "s-1_2"){?>
                                                                <?
                                                                $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();

                                                                if(!empty($lastmat)){?>
                                                                    <a style="height: 97px;width: 130px;padding-top: 22px;" href="#" data-toggle="modal" data-target="#confirmModal" type="submit" class="btn btn-success  ml-0"><?if(!empty($canp)){echo "Активировать место";}else{echo "Купить место $$price";}?></a>

                                                                <?}
                                                                ?>
                                                            <?}else{?>
                                                                <a href="#" class="user stretched-link" ></a>
                                                            <?}?>
                                                        </div>
                                                    <?}
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="item mb-5">
                                                <div class="icon">
                                                    <?
                                                    $empty = true;
                                                    if(!empty($item['shoulder2'])){
                                                        $s_matrix2 = \common\models\MatrixRef::findOne($item['shoulder2']);
                                                        if(!empty($s_matrix2)){
                                                            $empty = false;
                                                            if($last['id'] == $s_matrix2['id']){
                                                                $vacant = "s-2_$shoulder";
                                                            }
                                                        }

                                                    }
                                                    ?>
                                                    <?
                                                    if(!empty($item['shoulder2'])){?>
                                                        <?
                                                        $refmat2 = \common\models\MatParents::find()->where(['parent_id'=>$s_matrix2['id']])->count();
                                                        $shoulder2 = \common\models\User::findOne($s_matrix2['user_id']);
                                                        $refmat_own2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2['id'],'level'=>1,'activ'=>1])->count();
                                                        $mat_count2 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder2['id'],'platform_id'=>$m,'reinvest'=>1])->orderBy('platform_id desc')->all();
                                                        $mat_count_orig2 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder2['id'],'platform_id'=>$m,'reinvest'=>0])->orderBy('platform_id desc')->all();
                                                        ?>
                                                        <div class="modal fade" id="modal-ref-<?=$s_matrix2['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-<?=$s_matrix2['id']?>" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <?
                                                                            echo $shoulder2['username'];
                                                                            ?>
                                                                            <span style="color: #6e6e6e;">
                                                                        <?
                                                                        if ($s_matrix2['reinvest'] == 1) {
                                                                            echo "Clone";
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <table class="table table-striped">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">ФИО</td>
                                                                                <td><span style="float: right;"><?=$shoulder2['fio']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Телефон</td>
                                                                                <td><span style="float: right;"><?=$shoulder2['phone']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Площадка</td>
                                                                                <td><span style="float: right;"><?=$item['platform_id']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Дата активации</td>
                                                                                <td><span style="float: right;"><?=date('d.m.Y H:i',$s_matrix2['time'])?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Спонсор</td>
                                                                                <td><span style="float: right;"><?=\common\models\User::findOne($shoulder2['parent_id'])['username']?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                                <td><span style="float: right;"><?=$refmat2?></span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color: #6e6e6e;">Личники</td>
                                                                                <td><span style="float: right;"><?=$refmat_own2?></span></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div>
                                                                            <p class="mb-0 mr-4 pl-0 col-12">Клоны (<?=count($mat_count2)?>)</p>
                                                                            <div class="form-group">
                                                                                <select name="" id="clones" class="clones form-control">
                                                                                    <option value="0">Выберите клона</option>
                                                                                    <? foreach ($mat_count2 as $item_m) {?>
                                                                                        <option value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                                                                                    <?}?>
                                                                                </select>
                                                                            </div>
                                                                            <p class="mb-0 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig2)?>)</p>
                                                                            <div class="form-group">
                                                                                <select name="" id="clones" class="clones form-control">
                                                                                    <option value="0">Выберите место</option>
                                                                                    <? foreach ($mat_count_orig2 as $item_o) {?>
                                                                                        <option value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                                                                                    <?}?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <? $block2 = Matblocks::find()->where(['user_id'=>$main_user['id'],'mat_id'=>$s_matrix2['id']])->one();?>
                                                        <div class="icon <?if($s_matrix2['deleted'] == 1  or $empty){?>empty-mat<?}?> <?if(!empty($block2) or $empty){?>borderred<?}?>" >
                                                            <div class="number"><? echo sprintf("%'06d\n", $s_matrix2['id']);?></div>
                                                            <?

                                                            if ($m > $m_num) {
                                                                if (!empty($block2)) {
                                                                    ?>
                                                                    <a href="/profile/unblock?id=<?= $s_matrix2['id'] ?>"
                                                                       class="small unblock"> Разблокировать</a>
                                                                    <?
                                                                } else {
                                                                    ?>
                                                                    <a href="/profile/block?id=<?= $s_matrix2['id'] ?>"
                                                                       class="small block"> Заблокировать</a>
                                                                    <?
                                                                }
                                                            }?>
                                                            <? if ($s_matrix2['reinvest'] == 1) {?>
                                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                                    <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                                </svg>
                                                            <?}else{?>
                                                                <img style="width: 1rem;" src="/img/dollar.svg" alt="">
                                                            <?}?>
                                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix2['id']?>"></a>
                                                            <?
                                                            $num2 = \common\models\MatClons::find()->where(['mat_id'=>$s_matrix2['id']])->one();
                                                            ?>
                                                            <div class="name"><? echo $shoulder2['username'];?><?if(!empty($num2)){?>(<?=$num2['num']?>)<?}?></div>
                                                            <div class="main-link"><a style="color: #000" href="/profile/structure?c=<?=$s_matrix2['id']?>">***</a></div>
                                                        </div>

                                                    <?}else{?>
                                                        <div class="icon empty-mat" >
                                                            <?if($vacant == "s-2"){?>
                                                                <?
                                                                $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();

                                                                if(!empty($lastmat)){?>
                                                                    <a style="height: 97px;width: 130px;padding-top: 22px;" href="#" data-toggle="modal" data-target="#confirmModal" type="submit" class="btn btn-success  ml-0"><?if(!empty($canp)){echo "Активировать место";}else{echo "Купить место $$price";}?></a>

                                                                <?}?>
                                                            <?}else{?>
                                                                <a href="#" class="user stretched-link" ></a>
                                                            <?}?>
                                                        </div>
                                                    <?}
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="level">
                                                <div class="item">
                                                    <div class="icon">
                                                        <?
                                                        $empty = true;
                                                        if(!empty($item['shoulder2_1'])){
                                                            $s_matrix2_1 = \common\models\MatrixRef::findOne($item['shoulder2_1']);
                                                            if(!empty($s_matrix2_1)){
                                                                $empty = false;
                                                            }
                                                        }
                                                        ?>
                                                        <?
                                                        if(!empty($item['shoulder2_1'])){?>
                                                            <?
                                                            $refmat2_1 = \common\models\MatParents::find()->where(['parent_id'=>$s_matrix2_1['id']])->count();
                                                            $shoulder2_1 = \common\models\User::findOne($s_matrix2_1['user_id']);
                                                            $refmat_own2_1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2_1['id'],'level'=>1,'activ'=>1])->count();
                                                            $mat_count2_1 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder2_1['id'],'platform_id'=>$m,'reinvest'=>1])->orderBy('platform_id desc')->all();
                                                            $mat_count_orig2_1 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder2_1['id'],'platform_id'=>$m,'reinvest'=>0])->orderBy('platform_id desc')->all();
                                                            ?>
                                                            <div class="modal fade" id="modal-ref-<?=$s_matrix2_1['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-<?=$s_matrix2_1['id']?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                                <?
                                                                                echo $shoulder2_1['username'];
                                                                                ?>
                                                                                <span style="color: #6e6e6e;">
                                                                        <?
                                                                        if ($s_matrix2_1['reinvest'] == 1) {
                                                                            echo "Clone";
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                            </h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table table-striped">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">ФИО</td>
                                                                                    <td><span style="float: right;"><?=$shoulder2_1['fio']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Телефон</td>
                                                                                    <td><span style="float: right;"><?=$shoulder2_1['phone']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Площадка</td>
                                                                                    <td><span style="float: right;"><?=$item['platform_id']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Дата активации</td>
                                                                                    <td><span style="float: right;"><?=date('d.m.Y H:i',$s_matrix2_1['time'])?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Спонсор</td>
                                                                                    <td><span style="float: right;"><?=\common\models\User::findOne($shoulder2_1['parent_id'])['username']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                                    <td><span style="float: right;"><?=$refmat2_1?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Личники</td>
                                                                                    <td><span style="float: right;"><?=$refmat_own2_1?></span></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <div>
                                                                                <p class="mb-0 mr-4 pl-0 col-12">Клоны (<?=count($mat_count2_1)?>)</p>
                                                                                <div class="form-group">
                                                                                    <select name="" id="clones" class="clones form-control">
                                                                                        <option value="0">Выберите клона</option>
                                                                                        <? foreach ($mat_count2_1 as $item_m) {?>
                                                                                            <option value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                                                                                        <?}?>
                                                                                    </select>
                                                                                </div>
                                                                                <p class="mb-0 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig2_1)?>)</p>
                                                                                <div class="form-group">
                                                                                    <select name="" id="clones" class="clones form-control">
                                                                                        <option value="0">Выберите место</option>
                                                                                        <? foreach ($mat_count_orig2_1 as $item_o) {?>
                                                                                            <option value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                                                                                        <?}?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <? $block2_1 = Matblocks::find()->where(['user_id'=>$main_user['id'],'mat_id'=>$s_matrix2_1['id']])->one();?>
                                                            <div class="icon <?if($s_matrix2_1['deleted'] == 1 or $empty){?>empty-mat<?}?> <?if(!empty($block2_1) or $empty){?>borderred<?}?>" >
                                                                <div class="number"><? echo sprintf("%'06d\n", $s_matrix2_1['id']);?></div>
                                                                <?

                                                                if ($m > $m_num) {
                                                                    if (!empty($block2_1)) {
                                                                        ?>
                                                                        <a href="/profile/unblock?id=<?= $s_matrix2_1['id'] ?>"
                                                                           class="small unblock">
                                                                            Разблокировать</a>
                                                                        <?
                                                                    } else {
                                                                        ?>
                                                                        <a href="/profile/block?id=<?= $s_matrix2_1['id'] ?>"
                                                                           class="small block">
                                                                            Заблокировать</a>
                                                                        <?
                                                                    }
                                                                }?>
                                                                <? if ($s_matrix2_1['reinvest'] == 1) {?>
                                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                                        <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                                    </svg>
                                                                <?}else{?>
                                                                    <img style="width: 1rem;" src="/img/dollar.svg" alt="">
                                                                <?}?>
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix2_1['id']?>"></a>
                                                                <?
                                                                $num2_1 = \common\models\MatClons::find()->where(['mat_id'=>$s_matrix2_1['id']])->one();
                                                                ?>
                                                                <div class="name"><? echo $shoulder2_1['username'];?><?if(!empty($num2_1)){?>(<?=$num2_1['num']?>)<?}?></div>
                                                                <div class="main-link"><a style="color: #000" href="/profile/structure?c=<?=$s_matrix2_1['id']?>">***</a></div>
                                                            </div>

                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <?if($vacant == "s-2_1"){?>
                                                                    <?
                                                                    $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();

                                                                    if(!empty($lastmat)){?>
                                                                        <a style="height: 97px;width: 130px;padding-top: 22px;" href="#" type="submit" data-toggle="modal" data-target="#confirmModal" class="btn btn-success  ml-0"><?if(!empty($canp)){echo "Активировать место";}else{echo "Купить место $$price";}?></a>
                                                                    <?}
                                                                    ?>
                                                                <?}else{?>
                                                                    <a href="#" class="user stretched-link" ></a>
                                                                <?}?>
                                                            </div>
                                                        <?}
                                                        ?>

                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="icon">
                                                        <?
                                                        $empty = true;
                                                        if(!empty($item['shoulder2_2'])){
                                                            $s_matrix2_2 = \common\models\MatrixRef::findOne($item['shoulder2_2']);
                                                            if(!empty($s_matrix2_2)){
                                                                $empty = false;
                                                            }
                                                        }
                                                        ?>
                                                        <?
                                                        if(!empty($item['shoulder2_2'])){?>
                                                            <?
                                                            $refmat2_2 = \common\models\MatParents::find()->where(['parent_id'=>$s_matrix2_2['id']])->count();
                                                            $shoulder2_2 = \common\models\User::findOne($s_matrix2_2['user_id']);
                                                            $refmat_own2_2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2_2['id'],'level'=>1,'activ'=>1])->count();
                                                            $mat_count2_2 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1['id'],'platform_id'=>$m,'reinvest'=>1])->orderBy('platform_id desc')->all();
                                                            $mat_count_orig2_2 = \common\models\MatrixRef::find()->where(['user_id'=>$shoulder1['id'],'platform_id'=>$m,'reinvest'=>0])->orderBy('platform_id desc')->all();
                                                            ?>
                                                            <div class="modal fade" id="modal-ref-<?=$s_matrix2_2['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-<?=$s_matrix2_2['id']?>" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                                <?
                                                                                echo $shoulder2_2['username'];
                                                                                ?>
                                                                                <span style="color: #6e6e6e;">
                                                                                    <?
                                                                                    if ($s_matrix2_2['reinvest'] == 1) {
                                                                                        echo "Clone";
                                                                                    }
                                                                                    ?>
                                                                                </span>
                                                                            </h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table table-striped">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">ФИО</td>
                                                                                    <td><span style="float: right;"><?=$shoulder2_2['fio']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Телефон</td>
                                                                                    <td><span style="float: right;"><?=$shoulder2_2['phone']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Площадка</td>
                                                                                    <td><span style="float: right;"><?=$item['platform_id']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Дата активации</td>
                                                                                    <td><span style="float: right;"><?=date('d.m.Y H:i',$s_matrix2_2['time'])?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Спонсор</td>
                                                                                    <td><span style="float: right;"><?=\common\models\User::findOne($shoulder2_2['parent_id'])['username']?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                                    <td><span style="float: right;"><?=$refmat2_2?></span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="color: #6e6e6e;">Личники</td>
                                                                                    <td><span style="float: right;"><?=$refmat_own2_2?></span></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <div>
                                                                                <p class="mb-0 mr-4 pl-0 col-12">Клоны (<?=count($mat_count2_2)?>)</p>
                                                                                <div class="form-group">
                                                                                    <select name="" id="clones" class="clones form-control">
                                                                                        <option value="0">Выберите клона</option>
                                                                                        <? foreach ($mat_count2_2 as $item_m) {?>
                                                                                            <option value="<?=$item_m['id']?>">[<? echo sprintf("%'06d\n", $item_m['id']);?>] <?=date('d.m.Y H:i',$item_m['time'])?></option>
                                                                                        <?}?>
                                                                                    </select>
                                                                                </div>
                                                                                <p class="mb-0 mr-4 pl-0 col-12">Выкупленные места (<?=count($mat_count_orig2_2)?>)</p>
                                                                                <div class="form-group">
                                                                                    <select name="" id="clones" class="clones form-control">
                                                                                        <option value="0">Выберите место</option>
                                                                                        <? foreach ($mat_count_orig2_2 as $item_o) {?>
                                                                                            <option value="<?=$item_o['id']?>">[<? echo sprintf("%'06d\n", $item_o['id']);?>] <?=date('d.m.Y H:i',$item_o['time'])?></option>
                                                                                        <?}?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <? $block2_2 = Matblocks::find()->where(['user_id'=>$main_user['id'],'mat_id'=>$s_matrix2_2['id']])->one();?>
                                                            <div class="icon <?if($s_matrix2_2['deleted'] == 1 or $empty){?>empty-mat<?}?> <?if(!empty($block2_2) or $empty){?>borderred<?}?>" >
                                                                <div class="number"><? echo sprintf("%'06d\n", $s_matrix2_2['id']);?></div>
                                                                <?

                                                                if ($m > $m_num) {
                                                                    if (!empty($block2_2)) {
                                                                        ?>
                                                                        <a href="/profile/unblock?id=<?= $s_matrix2_2['id'] ?>"
                                                                           class="small unblock">
                                                                            Разблокировать</a>
                                                                        <?
                                                                    } else {
                                                                        ?>
                                                                        <a href="/profile/block?id=<?= $s_matrix2_2['id'] ?>"
                                                                           class="small block">
                                                                            Заблокировать</a>
                                                                        <?
                                                                    }
                                                                }?>
                                                                <? if ($s_matrix2_2['reinvest'] == 1) {?>
                                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                                        <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                                    </svg>
                                                                <?}else{?>
                                                                    <img style="width: 1rem;" src="/img/dollar.svg" alt="">
                                                                <?}?>
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix2_2['id']?>"></a>
                                                                <?
                                                                $num2_2 = \common\models\MatClons::find()->where(['mat_id'=>$s_matrix2_2['id']])->one();
                                                                ?>
                                                                <div class="name"><? echo $shoulder2_2['username'];?><?if(!empty($num2_2)){?>(<?=$num2_2['num']?>)<?}?></div>
                                                                <div class="main-link"><a style="color: #000" href="/profile/structure?c=<?=$s_matrix2_2['id']?>">***</a></div>
                                                            </div>

                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <?if($vacant == "s-2_2"){?>
                                                                    <?
                                                                    $canp = Canplatforms::find()->where(['platform'=>$m,'user_id'=>$main_user['id']])->one();

                                                                    if(!empty($lastmat)){?>
                                                                        <a style="height: 97px;width: 130px;padding-top: 22px;" href="#" data-toggle="modal" data-target="#confirmModal" type="submit" class="btn btn-success  ml-0"><?if(!empty($canp)){echo "Активировать место";}else{echo "Купить место $$price";}?></a>
                                                                    <?}
                                                                    ?>

                                                                <?}else{?>
                                                                    <a href="#" class="user stretched-link" ></a>
                                                                <?}?>
                                                            </div>
                                                        <?}
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </main>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "26rem";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Вы уверены что хотите выкупить/активировать место?</p>
                    <p>Стоимость места: <?=MLevelsNew::findOne($m)['price']?> US</p>
                    <p>Комиссия в токенах: <?=\common\models\TokenFees::findOne(['platform_id'=>$m])['fee_token']?> GRC</p>
                    <p>Ваш баланс: <?=$user['w_balans']?> US <a href="/profile/perfect" class="btn btn-link" target="_blank">Пополнить</a></p>
                    <p>Ваши токены: <?=$tokens?> GRC <a href="/tokens/get" class="btn btn-link" target="_blank">Пополнить</a></p>
                    <p>
                        <a href="/profile/getnewplatform?platform=<?=$m?>" class="btn btn-success">Да </a>
                        <button class="btn btn-danger" data-dismiss="modal">Нет </button>
                    </p>

                </div>

            </div>
        </div>
    </div><div class="modal fade" id="confirmModal2" tabindex="-1" role="dialog" aria-labelledby="confirmModal2Label" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body">
                <p>Вы уверены что хотите выкупить/активировать место?</p>
                <p>
                    <a href="/profile/getnewplatform?platform=<?=$m+1?>" class="btn btn-success">Да </a>
                    <button class="btn btn-danger" data-dismiss="modal">Нет </button>
                </p>

            </div>

        </div>
    </div>
</div>
<?
echo \frontend\components\LoginWidget::widget();
?>