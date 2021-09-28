<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;

$this->title = "Start матрица";
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',['depends'=>'yii\web\JqueryAsset']);
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne($user_id);
}
$mat = \common\models\MatrixStart::find()->where(['user_id'=>$user['id'],'reinvest'=>0])->orderBy('platform_id desc')->all();
$mat2 = \common\models\MatrixStart::find()->where(['user_id'=>$user['id'],'reinvest'=>0])->orderBy('platform_id asc')->all();

$mat_ref = \common\models\MatrixStart::find()->where(['user_id'=>$user['id'],'reinvest'=>1])->orderBy('platform_id desc')->all();
$mat2_ref = \common\models\MatrixStart::find()->where(['user_id'=>$user['id'],'reinvest'=>1])->orderBy('platform_id asc')->all();

 ?>
    <style>
        body{
            background-color: #1989F8!important;
        }
        .his.active {
            background: gray!important;
            color: #fff!important;
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
    <main class="structure-matrix">
        <div class="container">
            <div class="form-search d-flex justify-content-center align-items-center mt-5 flex-wrap">
                <h5 class="h5 mb-0 mr-4">Поиск по структуре</h5>
                <form action="/profile/start" class="form-inline">
                    <div class="form-group">
                        <input value="<?=$username?>" name="username" type="text" class="form-control" placeholder="Введите логин">
                        <button type="submit" class="btn btn-success ml-1">Поиск</button>
                    </div>
                </form>
            </div>
            <div class="hgroup pb-2 text-center">
                <h1 class="h3"><?if($user_id == Yii::$app->user->id){echo "Мои площадки";}else{echo "Площадки ".$user['username'];}?> Start</h1>
            </div>

            <ul class="nav nav-pills mb-5 justify-content-center" id="pills-tab" role="tablist">
            <?
            if(!empty($mat2)){
                $i = 0;
                foreach ($mat2 as $item2) {
                    $i++;
                    ?>
                <li class="nav-item " role="presentation">
                    <a <?if($item2['deleted'] == 1){?>style="color: #493e3e;" <?}?> class="nav-link <?if($item2['deleted'] == 1){?>his<?}?><?if($i==count($mat2)){echo "active";}?>" id="tab-<?=$item2['id']?>-tab" data-toggle="pill" href="#tab-<?=$item2['id']?>" role="tab" aria-controls="tab-<?=$item2['id']?>" aria-selected="true">Площадка <?=$item2['platform_id']?></a>
                </li>
                <?}}?>
                <?if($user_id == Yii::$app->user->id and $item2['platform_id'] < 4){?>
                    <li class="nav-item " role="presentation">
                        <a style="color: #493e3e;" class="nav-link" id="tab-<?=$item2['id']?>-tab" data-toggle="pill" href="#tab-new" role="tab" aria-controls="tab-new" aria-selected="true">Площадка <?=$item2['platform_id']+1?></a>
                    </li>
                <?}?>

            </ul>

            <div class="tab-content" id="pills-tabContent">
            <?
            if(!empty($mat)){
                $i = 0;
                foreach ($mat as $item) {
                    $i++;
                    ?>
                    <div class="tab-pane fade <?if($i==1){echo "show active";}?>" id="tab-<?=$item['id']?>" role="tabpanel" aria-labelledby="tab-<?=$item['id']?>">
                        <div class="desh">
                            <div class="wrap-matrix">
                                <div class="item">
                                    <div class="modal fade" id="modal-user-<?=$item['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-user-<?=$item['id']?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        <?=$user['username']?>
                                                        <span style="color: #6e6e6e;">
                                                            <?
                                                            if ($item['reinvest'] == 1) {
                                                                echo "Reinvest";
                                                            }
                                                            ?>
                                                        </span>
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <?
                                                    $refmat = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                    $refmat_own = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'level'=>1,'activ'=>1])->count();
                                                    ?>
                                                    <table class="table table-striped">
                                                        <tbody>
                                                        <tr>
                                                            <td style="color: #6e6e6e;">Площадка</td>
                                                            <td><span style="float: right;"><?=$item['platform_id']?></span></td>
                                                        </tr>
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
                                    <div class="icon <?if($item['deleted'] == 1){?>empty-mat<?}?>">
                                        <div class="number"><?=$refmat?></div>
                                        <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-user-<?=$item['id']?>"></a>
                                        <div class="name"><?=$user['username']?></div>
                                    </div>

                                </div>
                                <div class="shoulder">
                                    <div class="left">
                                        <div class="item">
                                            <?
                                            $empty = true;
                                            if(!empty($item['shoulder1'])){
                                                $s_matrix1 = \common\models\MatrixStart::findOne($item['shoulder1']);
                                                if(!empty($s_matrix1)){
                                                    $empty = false;
                                                }
                                            }
                                            ?>
                                            <?
                                            if(!empty($item['shoulder1'])){?>
                                                <?
                                                $refmat1 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix1['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                $shoulder1 = \common\models\User::findOne($s_matrix1['user_id']);
                                                $refmat_own1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1['id'],'level'=>1,'activ'=>1])->count();
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
                                                                        if ($item['reinvest'] == 1) {
                                                                            echo "Reinvest";
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

                                                                <a href="/profile/start/<?= $shoulder1['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="icon <?if($s_matrix1['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                    <div class="number"><?=$refmat1?></div>
                                                    <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix1['id']?>"></a>
                                                    <div class="name"><?=$shoulder1['username']?></div>
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
                                                <?
                                                $empty = true;
                                                if(!empty($item['shoulder1_1'])){
                                                    $s_matrix1_1 = \common\models\MatrixStart::findOne($item['shoulder1_1']);
                                                    if(!empty($s_matrix1_1)){
                                                        $empty = false;
                                                    }
                                                }
                                                ?>
                                                <?
                                                if(!empty($item['shoulder1_1'])){?>
                                                    <?
                                                    $refmat1_1 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix1_1['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                    $shoulder1_1 = \common\models\User::findOne($s_matrix1_1['user_id']);
                                                    $refmat_own1_1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1_1['id'],'level'=>1,'activ'=>1])->count();
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
                                                                        if ($item['reinvest'] == 1) {
                                                                            echo "Reinvest";
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

                                                                    <a href="/profile/start/<?= $shoulder1_1['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="icon <?if($s_matrix1_1['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                        <div class="number"><?=$refmat1_1?></div>
                                                        <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix1_1['id']?>"></a>
                                                        <div class="name"><?=$shoulder1_1['username']?></div>
                                                    </div>

                                                <?}else{?>
                                                    <div class="icon empty-mat" >
                                                        <a href="#" class="user stretched-link"></a>
                                                    </div>
                                                <?}
                                                ?>

                                            </div>
                                            <div class="item">
                                                <?
                                                $empty = true;
                                                if(!empty($item['shoulder1_2'])){
                                                    $s_matrix1_2 = \common\models\MatrixStart::findOne($item['shoulder1_2']);
                                                    if(!empty($s_matrix1_2)){
                                                        $empty = false;
                                                    }
                                                }
                                                ?>
                                                <?
                                                if(!empty($item['shoulder1_2'])){?>
                                                    <?
                                                    $refmat1_2 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix1_2['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                    $shoulder1_2 = \common\models\User::findOne($s_matrix1_2['user_id']);
                                                    $refmat_own1_2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1_2['id'],'level'=>1,'activ'=>1])->count();
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
                                                                        if ($item['reinvest'] == 1) {
                                                                            echo "Reinvest";
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

                                                                    <a href="/profile/start/<?= $shoulder1_2['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="icon <?if($s_matrix1_2['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                        <div class="number"><?=$refmat1_2?></div>
                                                        <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix1_2['id']?>"></a>
                                                        <div class="name"><?=$shoulder1_2['username']?></div>
                                                    </div>

                                                <?}else{?>
                                                    <div class="icon empty-mat" >
                                                        <a href="#" class="user stretched-link"></a>
                                                    </div>
                                                <?}
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class="item">
                                            <div class="icon">
                                                <?
                                                $empty = true;
                                                if(!empty($item['shoulder2'])){
                                                    $s_matrix2 = \common\models\MatrixStart::findOne($item['shoulder2']);
                                                    if(!empty($s_matrix2)){
                                                        $empty = false;
                                                    }
                                                }
                                                ?>
                                                <?
                                                if(!empty($item['shoulder2'])){?>
                                                    <?
                                                    $refmat2 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix2['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                    $shoulder2 = \common\models\User::findOne($s_matrix2['user_id']);
                                                    $refmat_own2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2['id'],'level'=>1,'activ'=>1])->count();
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
                                                                        if ($item['reinvest'] == 1) {
                                                                            echo "Reinvest";
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

                                                                    <a href="/profile/start/<?= $shoulder2['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="icon <?if($s_matrix2['deleted'] == 1  or $empty){?>empty-mat<?}?>" >
                                                        <div class="number"><?=$refmat2?></div>
                                                        <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix2['id']?>"></a>
                                                        <div class="name"><?=$shoulder2['username']?></div>
                                                    </div>

                                                <?}else{?>
                                                    <div class="icon empty-mat" >
                                                        <a href="#" class="user stretched-link"></a>
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
                                                        $s_matrix2_1 = \common\models\MatrixStart::findOne($item['shoulder2_1']);
                                                        if(!empty($s_matrix2_1)){
                                                            $empty = false;
                                                        }
                                                    }
                                                    ?>
                                                    <?
                                                    if(!empty($item['shoulder2_1'])){?>
                                                        <?
                                                        $refmat2_1 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix2_1['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                        $shoulder2_1 = \common\models\User::findOne($s_matrix2_1['user_id']);
                                                        $refmat_own2_1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2_1['id'],'level'=>1,'activ'=>1])->count();
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
                                                                        if ($item['reinvest'] == 1) {
                                                                            echo "Reinvest";
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

                                                                        <a href="/profile/start/<?= $shoulder2_1['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="icon <?if($s_matrix2_1['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                            <div class="number"><?=$refmat2_1?></div>
                                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix2_1['id']?>"></a>
                                                            <div class="name"><?=$shoulder2_1['username']?></div>
                                                        </div>

                                                    <?}else{?>
                                                        <div class="icon empty-mat" >
                                                            <a href="#" class="user stretched-link"></a>
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
                                                        $s_matrix2_2 = \common\models\MatrixStart::findOne($item['shoulder2_2']);
                                                        if(!empty($s_matrix2_2)){
                                                            $empty = false;
                                                        }
                                                    }
                                                    ?>
                                                    <?
                                                        if(!empty($item['shoulder2_2'])){?>
                                                            <?
                                                            $refmat2_2 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix2_2['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                            $shoulder2_2 = \common\models\User::findOne($s_matrix2_2['user_id']);
                                                            $refmat_own2_2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2_2['id'],'level'=>1,'activ'=>1])->count();
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
                                                                                    if ($item['reinvest'] == 1) {
                                                                                        echo "Reinvest";
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

                                                                            <a href="/profile/start/<?= $shoulder2_2['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="icon <?if($s_matrix2_2['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                                <div class="number"><?=$refmat2_2?></div>
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-<?=$s_matrix2_2['id']?>"></a>
                                                                <div class="name"><?=$shoulder2_2['username']?></div>
                                                            </div>

                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <a href="#" class="user stretched-link"></a>
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
             <?}}?>
                <?if($user_id == Yii::$app->user->id){?>
                    <div class="tab-pane fade" id="tab-new" role="tabpanel" aria-labelledby="tab-new">
                        <div class="desh">
                            <a href="/profile/getnewplatformstart" class="btn-success btn">Выкупить место на площадке</a>
                        </div>
                    </div>
                <?}?>

            </div>

        </div>
    </main>
    <? if(!empty($mat_ref)){?>
        <main class="structure-matrix">
            <div class="container">
                <div class="hgroup pb-2 text-center">
                    <h1 class="h3"><?if($user_id == Yii::$app->user->id){echo "Мои реинвесты";}else{echo "Реинвесты ".$user['username'];}?></h1>
                </div>

                <ul class="nav nav-pills mb-5 justify-content-center" id="pills-tab" role="tablist">
                    <?
                    if(!empty($mat2_ref)){
                        $i = 0;
                        foreach ($mat2_ref as $item2_ref) {
                            $i++;
                            ?>
                            <li class="nav-item" role="presentation">
                                <a <?if($item2_ref['deleted'] == 1){?>style="color: #493e3e;" <?}?> class="nav-link <?if($item2_ref['deleted'] == 1){?>his<?}?> <?if($i==count($mat2_ref)){echo "active";}?>" id="tabref-<?=$item2_ref['id']?>-tab" data-toggle="pill" href="#tabref-<?=$item2_ref['id']?>" role="tab" aria-controls="tabref-<?=$item2_ref['id']?>" aria-selected="true">Площадка <?=$item2_ref['platform_id']?></a>
                            </li>
                        <?}}?>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <?
                    if(!empty($mat_ref)){
                        $i = 0;
                        foreach ($mat_ref as $item_ref) {
                            $i++;
                            ?>
                            <div class="tab-pane fade <?if($i==1){echo "show active";}?>" id="tabref-<?=$item_ref['id']?>" role="tabpanel" aria-labelledby="tabref-<?=$item_ref['id']?>">
                                <div class="desh">
                                    <div class="wrap-matrix">
                                        <div class="item">
                                            <div class="modal fade" id="modal-user-re<?=$item_ref['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-user-re<?=$item_ref['id']?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"><?=$user['username']?>
                                                                <span style="color: #6e6e6e;">
                                                                    <?
                                                                    if ($item_ref['reinvest'] == 1) {
                                                                        echo "Reinvest";
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
                                                                    <td><span style="float: right;"><?=$item_ref['platform_id']?></span></td>
                                                                </tr>
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
                                            <div class="icon <?if($item_ref['deleted'] == 1){?>empty-mat<?}?>" >
                                                <div class="number"><?=$refmat?></div>
                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-user-re<?=$item_ref['id']?>"></a>
                                                <div class="name"><?=$user['username']?></div>
                                            </div>

                                        </div>
                                        <div class="shoulder">
                                            <div class="left">
                                                <div class="item">
                                                    <?
                                                    $empty = true;
                                                    if(!empty($item_ref['shoulder1'])){
                                                        $s_matrix1 = \common\models\MatrixStart::findOne($item_ref['shoulder1']);
                                                        if(!empty($s_matrix1)){
                                                            $empty = false;
                                                        }
                                                    }
                                                    ?>
                                                    <?
                                                    if(!empty($item_ref['shoulder1'])){?>
                                                        <?
                                                        $refmat1 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix1['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                        $shoulder1 = \common\models\User::findOne($s_matrix1['user_id']);
                                                        $refmat_own1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1['id'],'level'=>1,'activ'=>1])->count();
                                                        ?>
                                                        <div class="modal fade" id="modal-ref-re<?=$s_matrix1['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-re<?=$s_matrix1['id']?>" aria-hidden="true">
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
                                                                                        echo "Reinvest";
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
                                                                                <td><span style="float: right;"><?=$item_ref['platform_id']?></span></td>
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

                                                                        <a href="/profile/start/<?= $shoulder1['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="icon <?if($s_matrix1['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                            <div class="number"><?=$refmat1?></div>
                                                            <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-re<?=$s_matrix1['id']?>"></a>
                                                            <div class="name"><?=$shoulder1['username']?></div>
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
                                                        <?
                                                        $empty = true;
                                                        if(!empty($item_ref['shoulder1_1'])){
                                                            $s_matrix1_1 = \common\models\MatrixStart::findOne($item_ref['shoulder1_1']);
                                                            if(!empty($s_matrix1_1)){
                                                                $empty = false;
                                                            }
                                                        }
                                                        ?>
                                                        <?
                                                        if(!empty($item_ref['shoulder1_1'])){?>
                                                            <?
                                                            $refmat1_1 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix1_1['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                            $shoulder1_1 = \common\models\User::findOne($s_matrix1_1['user_id']);
                                                            $refmat_own1_1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1_1['id'],'level'=>1,'activ'=>1])->count();
                                                            ?>
                                                            <div class="modal fade" id="modal-ref-re<?=$s_matrix1_1['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-re<?=$s_matrix1_1['id']?>" aria-hidden="true">
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
                                                                                        echo "Reinvest";
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
                                                                                    <td><span style="float: right;"><?=$item_ref['platform_id']?></span></td>
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

                                                                            <a href="/profile/start/<?= $shoulder1_1['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="icon <?if($s_matrix1_1['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                                <div class="number"><?=$refmat1_1?></div>
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-re<?=$s_matrix1_1['id']?>"></a>
                                                                <div class="name"><?=$shoulder1_1['username']?></div>
                                                            </div>

                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <a href="#" class="user stretched-link"></a>
                                                            </div>
                                                        <?}
                                                        ?>

                                                    </div>
                                                    <div class="item">
                                                        <?
                                                        $empty = true;
                                                        if(!empty($item_ref['shoulder1_2'])){
                                                            $s_matrix1_2 = \common\models\MatrixStart::findOne($item_ref['shoulder1_2']);
                                                            if(!empty($s_matrix1_2)){
                                                                $empty = false;
                                                            }
                                                        }
                                                        ?>
                                                        <?
                                                        if(!empty($item_ref['shoulder1_2'])){?>
                                                            <?
                                                            $refmat1_2 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix1_2['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                            $shoulder1_2 = \common\models\User::findOne($s_matrix1_2['user_id']);
                                                            $refmat_own1_2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder1_2['id'],'level'=>1,'activ'=>1])->count();
                                                            ?>
                                                            <div class="modal fade" id="modal-ref-re<?=$s_matrix1_2['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-re<?=$s_matrix1_2['id']?>" aria-hidden="true">
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
                                                                                        echo "Reinvest";
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
                                                                                    <td><span style="float: right;"><?=$item_ref['platform_id']?></span></td>
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

                                                                            <a href="/profile/start/<?= $shoulder1_2['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="icon <?if($s_matrix1_2['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                                <div class="number"><?=$refmat1_2?></div>
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-re<?=$s_matrix1_2['id']?>"></a>
                                                                <div class="name"><?=$shoulder1_2['username']?></div>
                                                            </div>

                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <a href="#" class="user stretched-link"></a>
                                                            </div>
                                                        <?}
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="right">
                                                <div class="item">
                                                    <div class="icon">
                                                        <?
                                                        $empty = true;
                                                        if(!empty($item_ref['shoulder2'])){
                                                            $s_matrix2 = \common\models\MatrixStart::findOne($item_ref['shoulder2']);
                                                            if(!empty($s_matrix2)){
                                                                $empty = false;
                                                            }
                                                        }
                                                        ?>
                                                        <?
                                                        if(!empty($item_ref['shoulder2'])){?>
                                                            <?
                                                            $refmat2 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix2['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                            $shoulder2 = \common\models\User::findOne($s_matrix2['user_id']);
                                                            $refmat_own2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2['id'],'level'=>1,'activ'=>1])->count();
                                                            ?>
                                                            <div class="modal fade" id="modal-ref-re<?=$s_matrix2['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-re<?=$s_matrix2['id']?>" aria-hidden="true">
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
                                                                                        echo "Reinvest";
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
                                                                                    <td><span style="float: right;"><?=$item_ref['platform_id']?></span></td>
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

                                                                            <a href="/profile/start/<?= $shoulder2['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="icon <?if($s_matrix2['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                                <div class="number"><?=$refmat2?></div>
                                                                <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-re<?=$s_matrix2['id']?>"></a>
                                                                <div class="name"><?=$shoulder2['username']?></div>
                                                            </div>

                                                        <?}else{?>
                                                            <div class="icon empty-mat" >
                                                                <a href="#" class="user stretched-link"></a>
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
                                                            if(!empty($item_ref['shoulder2_1'])){
                                                                $s_matrix2_1 = \common\models\MatrixStart::findOne($item_ref['shoulder2_1']);
                                                                if(!empty($s_matrix2_1)){
                                                                    $empty = false;
                                                                }
                                                            }
                                                            ?>
                                                            <?
                                                            if(!empty($item_ref['shoulder2_1'])){?>
                                                                <?
                                                                $refmat2_1 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix2_1['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                                $shoulder2_1 = \common\models\User::findOne($s_matrix2_1['user_id']);
                                                                $refmat_own2_1 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2_1['id'],'level'=>1,'activ'=>1])->count();
                                                                ?>
                                                                <div class="modal fade" id="modal-ref-re<?=$s_matrix2_1['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-re<?=$s_matrix2_1['id']?>" aria-hidden="true">
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
                                                                                        echo "Reinvest";
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
                                                                                        <td><span style="float: right;"><?=$item_ref['platform_id']?></span></td>
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

                                                                                <a href="/profile/start/<?= $shoulder2_1['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="icon <?if($s_matrix2_1['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                                    <div class="number"><?=$refmat2_1?></div>
                                                                    <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-re<?=$s_matrix2_1['id']?>"></a>
                                                                    <div class="name"><?=$shoulder2_1['username']?></div>
                                                                </div>

                                                            <?}else{?>
                                                                <div class="icon empty-mat" >
                                                                    <a href="#" class="user stretched-link"></a>
                                                                </div>
                                                            <?}
                                                            ?>

                                                        </div>
                                                    </div>
                                                    <div class="item">
                                                        <div class="icon">
                                                            <?
                                                            $empty = true;
                                                            if(!empty($item_ref['shoulder2_2'])){
                                                                $s_matrix2_2 = \common\models\MatrixStart::findOne($item_ref['shoulder2_2']);
                                                                if(!empty($s_matrix2_2)){
                                                                    $empty = false;
                                                                }
                                                            }
                                                            ?>
                                                            <?
                                                            if(!empty($item_ref['shoulder2_2'])){?>
                                                                <?
                                                                $refmat2_2 = \common\models\Referals::find()->where(['parent_id'=>$s_matrix2_2['user_id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
                                                                $shoulder2_2 = \common\models\User::findOne($s_matrix2_2['user_id']);
                                                                $refmat_own2_2 = \common\models\Referals::find()->where(['parent_id'=>$shoulder2_2['id'],'level'=>1,'activ'=>1])->count();
                                                                ?>
                                                                <div class="modal fade" id="modal-ref-re<?=$s_matrix2_2['id']?>" tabindex="-1" role="dialog" aria-labelledby="modal-ref-re<?=$s_matrix2_2['id']?>" aria-hidden="true">
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
                                                                                        echo "Reinvest";
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
                                                                                        <td><span style="float: right;"><?=$item_ref['platform_id']?></span></td>
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

                                                                                <a href="/profile/start/<?= $shoulder2_2['id'] ?>" type="button" class="btn btn-success center-block">Развернуть структуру</a>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="icon <?if($s_matrix2_2['deleted'] == 1 or $empty){?>empty-mat<?}?>" >
                                                                    <div class="number"><?=$refmat2_2?></div>
                                                                    <a href="#" class="user stretched-link" data-toggle="modal" data-target="#modal-ref-re<?=$s_matrix2_2['id']?>"></a>
                                                                    <div class="name"><?=$shoulder2_2['username']?></div>
                                                                </div>

                                                            <?}else{?>
                                                                <div class="icon empty-mat" >
                                                                    <a href="#" class="user stretched-link"></a>
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
                        <?}}?>
                </div>
            </div>
        </main>
    <?} ?>
<?
echo \frontend\components\LoginWidget::widget();
?>