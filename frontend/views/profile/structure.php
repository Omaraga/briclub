<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Матрица Global";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$buy = false;
$st = false;
$order = null;
if($user['global'] == 1){
    $mat = \common\models\UserPlatforms::find()->where(['user_id'=>$user['id'],'deleted'=>2])->one();
    $pre_mats = \common\models\UserPlatforms::find()->where(['deleted'=>2,'platform_id'=>$mat['platform_id']])->andWhere(['<','id',$mat['id']])->all();
    if($mat['platform_id']>2){
        $order = count($pre_mats) + 1;
    }

    $news = 6;
    foreach ($pre_mats as $pre_mat) {
        $news = $news + (6-$pre_mat['slots']);
    }
    if(!empty($mat)){
        $children1 = \common\models\UserPlatforms::find()->where(['parent_id'=>$mat['id']])->all();
    }

    $refs = \common\models\User::find()->where(['parent_id'=>$user['id']])->all();
    $st = true;
}else{
    $buy = true;
}

if(isset($children1[0]['children']) and $children1[0]['children']>0){
    $children2 = \common\models\UserPlatforms::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();

}
if(isset($children1[1]['children']) and $children1[1]['children']>0){
    $children3 = \common\models\UserPlatforms::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
}



?>
    <style>

        .his.active {
            background: gray!important;
            color: #fff!important;
        }
        .empty-mat{
            background: #d4d4d5!important;
        }
        .empty-mat svg{
            background: #d4d4d5!important;
        }
        .avatar-block {
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            background-color: #F2F3FF;
            padding: .5rem .5rem;
            border-radius: .25rem; }
        .avatar-block-wrap {
            padding-left: 1rem; }
        .avatar-block .avatar-icon {
            background: #258FFC;
            border-radius: 90%;
            padding: .75rem; }
        .structure {
            width: 100%;
            overflow: auto; }
        .structure-wrap {
            min-width: 800px; }
    </style>
    <main class="structure-matrix">
        <div class="container">
            <div class="hgroup pb-2 text-center">
                <h1 class="h3">Мои площадка Global</h1>
            </div>

            <ul class="nav nav-pills mb-5 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item " role="presentation">
                    <a class="nav-link active" id="tab-tab" data-toggle="pill" href="#tab" role="tab" aria-controls="tab" aria-selected="true">
                        Площадка
                        <?
                        $children = \common\models\User::find()->where(['parent_id'=>$user['id'],'activ'=>1])->all();
                        $global = \common\models\UserPlatforms::find()->where(['user_id'=>$user['id'],'deleted'=>2])->orderBy('platform_id desc')->one();
                        if(!empty($global)){
                            if(count($children)>1){
                                if($global['platform_id'] == 1){
                                    echo $global['platform_id'];
                                }else{
                                    echo $global['platform_id']-1;
                                }
                            }else{
                                echo $global['platform_id']-1;
                            }
                        }
                        ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane show active" id="tab" role="tabpanel" aria-labelledby="tab">
                            <div class="desh">
                                <div class="wrap-matrix">
                                    <div class="item">
                                        <div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="modal-user" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            <?=$user['username']?>
                                                            <span style="color: #6e6e6e;">
                                                        </span>
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <?
                                                        $refmat = \common\models\Referals::find()->where(['parent_id'=>$user['id']])->count();
                                                        $refmat_own = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'level'=>1])->count();
                                                        ?>
                                                        <table class="table table-striped">
                                                            <tbody>
                                                            <tr>
                                                                <td style="color: #6e6e6e;">Площадка</td>
                                                                <td>
                                                                    <span style="float: right;">
                                                                        <?
                                                                        $children = \common\models\User::find()->where(['parent_id'=>$user['id'],'activ'=>1])->all();
                                                                        $global = \common\models\UserPlatforms::find()->where(['user_id'=>$user['id'],'deleted'=>2])->orderBy('platform_id desc')->one();
                                                                        if(!empty($global)){
                                                                            if(count($children)>1){
                                                                                if($global['platform_id'] == 1){
                                                                                    echo $global['platform_id'];
                                                                                }else{
                                                                                    echo $global['platform_id']-1;
                                                                                }
                                                                            }else{
                                                                                echo $global['platform_id']-1;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <?if(!empty($order)){?>
                                                                <tr>
                                                                    <td style="color: #6e6e6e;">Очередь</td>
                                                                    <td><span style="float: right;"><?=$order?></span></td>
                                                                </tr>
                                                            <?}?>
                                                            <tr>
                                                                <td style="color: #6e6e6e;">Спонсор</td>
                                                                <td><span style="float: right;"><?=\common\models\User::findOne($user['parent_id'])['username']?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="color: #6e6e6e;">Людей в структуре</td>
                                                                <td><span style="float: right;"><?=$refmat?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="color: #6e6e6e;">Личники</td>
                                                                <td><span style="float: right;"><?=$refmat_own?></span></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="icon">
                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-user"></a>
                                            <div class="name"><?=$user['username']?></div>
                                        </div>
                                        <div class="number"><?=$refmat?></div>
                                    </div>
                                    <div class="shoulder">
                                        <div class="left">
                                            <div class="item">

                                                <?
                                                if(isset($children1[0])){
                                                    $shoulder1 = \common\models\User::findOne($children1[0]['user_id']);
                                                    ?>
                                                    <div class="modal fade" id="modal-ref-1" tabindex="-1" role="dialog" aria-labelledby="modal-ref-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        <? echo $shoulder1['username'];?>
                                                                        <span style="color: #6e6e6e;">
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
                                                                            <td><span style="float: right;"><?=$children1[0]['platform_id']-1?></span></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="icon <?if(!isset($children1[0])){?>empty-mat<?}?>" >
                                                        <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-1"></a>
                                                        <div class="name"><? echo $shoulder1['username'];?></div>
                                                    </div>
                                                <?}else{?>
                                                    <div class="icon empty-mat" >
                                                        <a href="#" class="user stretched-link"></a>
                                                    </div>
                                                <?}?>

                                            </div>
                                            <div class="level">
                                                <div class="item">
                                                    <?
                                                    if($user['id'] == 8858 and $mat['platform_id'] == 3){
                                                        $children2[0] = array();
                                                        $children2[0]['id'] = 101;
                                                        $children2[0]['user_id'] = 8813;
                                                        $children2[0]['platform_id'] = 3;
                                                    }
                                                    if(isset($children2[0])){
                                                        $shoulder1_1 = \common\models\User::findOne($children2[0]['user_id']);
                                                        ?>
                                                        <div class="modal fade" id="modal-ref-1_1" tabindex="-1" role="dialog" aria-labelledby="modal-ref-1_1" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <?
                                                                            echo $shoulder1_1['username'];
                                                                            ?>
                                                                            <span style="color: #6e6e6e;">
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
                                                                                <td><span style="float: right;"><?=$children2[0]['platform_id']-1?></span></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="icon <?if(!isset($children2[0])){?>empty-mat<?}?>" >
                                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-1_1"></a>
                                                            <div class="name"><? echo $shoulder1_1['username'];?></div>
                                                        </div>
                                                    <?}else{?>
                                                        <div class="icon empty-mat" >
                                                            <a href="#" class="user stretched-link"></a>
                                                        </div>
                                                    <?}?>
                                                </div>
                                                <div class="item">
                                                    <?
                                                    if(isset($children2[1])){
                                                        $shoulder1_2 = \common\models\User::findOne($children2[1]['user_id']);
                                                        ?>
                                                        <div class="modal fade" id="modal-ref-1_2" tabindex="-1" role="dialog" aria-labelledby="modal-ref-1_2" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <?
                                                                            echo $shoulder1_2['username'];
                                                                            ?>
                                                                            <span style="color: #6e6e6e;">
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
                                                                                <td><span style="float: right;"><?=$children2[1]['platform_id']-1?></span></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="icon <?if(!isset($children2[1])){?>empty-mat<?}?>" >
                                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-1_2"></a>
                                                            <div class="name"><? echo $shoulder1_2['username'];?></div>
                                                        </div>
                                                    <?}else{?>
                                                        <div class="icon empty-mat" >
                                                            <a href="#" class="user stretched-link"></a>
                                                        </div>
                                                    <?}?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="item">
                                                <?
                                                if(isset($children1[1])){
                                                    $shoulder2 = \common\models\User::findOne($children1[1]['user_id']);
                                                    ?>
                                                    <div class="modal fade" id="modal-ref-2" tabindex="-1" role="dialog" aria-labelledby="modal-ref-2" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        <?
                                                                        echo $shoulder2['username'];
                                                                        ?>
                                                                        <span style="color: #6e6e6e;">
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
                                                                            <td><span style="float: right;"><?=$children1[1]['platform_id']-1?></span></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="icon <?if(!isset($children1[1])){?>empty-mat<?}?>" >
                                                        <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-2"></a>
                                                        <div class="name"><? echo $shoulder2['username'];?></div>
                                                    </div>
                                                <?}else{?>
                                                    <div class="icon empty-mat" >
                                                        <a href="#" class="user stretched-link"></a>
                                                    </div>
                                                <?}
                                                ?>
                                            </div>
                                            <div class="level">
                                                <div class="item">
                                                    <div class="icon">
                                                        <?
                                                        if(isset($children3[0])){
                                                            $shoulder2_1 = \common\models\User::findOne($children3[0]['user_id']);
                                                            ?>
                                                            <div class="modal fade" id="modal-ref-2_1" tabindex="-1" role="dialog" aria-labelledby="modal-ref-2_1" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                                <?
                                                                                echo $shoulder2_1['username'];
                                                                                ?>
                                                                                <span style="color: #6e6e6e;">
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
                                                                                    <td><span style="float: right;"><?=$children3[0]['platform_id']-1?></span></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="icon <?if(!isset($children3[0])){?>empty-mat<?}?>" >
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-2_1"></a>
                                                                <div class="name"><? echo $shoulder2_1['username'];?></div>
                                                            </div>
                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <a href="#" class="user stretched-link"></a>
                                                            </div>
                                                        <?}?>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="icon">
                                                        <?
                                                        if(isset($children3[1])){
                                                            $shoulder2_2 = \common\models\User::findOne($children3[1]['user_id']);
                                                            ?>
                                                            <div class="modal fade" id="modal-ref-2_2" tabindex="-1" role="dialog" aria-labelledby="modal-ref-2_2" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                                <?
                                                                                echo $shoulder2_2['username'];
                                                                                ?>
                                                                                <span style="color: #6e6e6e;">
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
                                                                                    <td><span style="float: right;"><?=$children3[1]['platform_id']-1?></span></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="icon <?if(!isset($children3[1])){?>empty-mat<?}?>" >
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-2_2"></a>
                                                                <div class="name"><? echo $shoulder2_2['username'];?></div>
                                                            </div>
                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <a href="#" class="user stretched-link"></a>
                                                            </div>
                                                        <?}?>
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

<?
echo \frontend\components\LoginWidget::widget();
?>