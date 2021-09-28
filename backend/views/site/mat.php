<?php

/* @var $this yii\web\View */

use common\models\Matblocks;
use common\models\MatParents;
use common\models\MatrixRef;
use common\models\User;
use kartik\typeahead\TypeaheadBasic;

/*$mats = \common\models\MatrixRef::find()->where(['platform_id'=>6])->all();
foreach ($mats as $mat) {
    echo $mat['id'];
    echo "<br>";
    $max_level = MatParents::find()->where(['parent_id'=>$mat['id']])->orderBy('level desc')->one()['level'];
    for($i=1;$i<=$max_level;$i++){
        $count = pow(2,$i);

        $n = [1, 2];
        $line = $i;
        $list = \common\models\MatrixRef::pwr($line,$n);

        $k = 1;
        foreach ($list as $item) {
            $ch_mat = \common\models\MatrixRef::getForParent($mat['id'],$item);
            if(!empty($ch_mat)){
                $par = MatParents::find()->where(['mat_id'=>$ch_mat['id'],'parent_id'=>$mat['id']])->one();
                if(!empty($par)){
                    $par->order = $k;
                    $par->save();
                }

            }


            $k++;
        }

    }
    echo "<hr>";
}
exit;*/



$this->title = 'Матрица';
$main_user_id=null;
$max_level = MatParents::find()->where(['parent_id'=>$mat['id']])->orderBy('level desc')->one()['level'];
$ch_mats = MatParents::find()->where(['parent_id'=>$mat['id']])->orderBy('order asc')->all();
$array_list = array();
foreach ($ch_mats as $ch_mat) {
    $array_list[$ch_mat['level']][$ch_mat['order']] = $ch_mat['mat_id'];
}
$user = User::findOne($mat['user_id']);
$this->registerJs('
    $(\'#upscrl\').click(function() {
     zoom = $("#main").css(\'zoom\');
     zoom = zoom*(1.1);
     $("#main").css(\'zoom\',zoom);
    });
    $(\'#downscrl\').click(function() {
     zoom = $("#main").css(\'zoom\');
     zoom = zoom/(1.1);
     $("#main").css(\'zoom\',zoom);
    });
');
?>

<style>
    #mas{
        height: 50px;
        position: fixed;
    }
    .item{
        width: 90px;
        height: auto;
        min-height: 35px;
        background-color: #fafafa;
        margin: 0 auto;
        display: inline-block;
        padding: 2px;
        font-size: 11px;
        border-radius: 3px;
        box-shadow: 2px 2px #ccc;
    }
    .empty{
        background-color: #6c7781;
    }
    .line{
        text-align: center;
        padding-top: 30px;
        <?
        if(count($array_list)>0){?>
        width: <?=(pow(2,$max_level))*94?>px;
        <?}else{?>
        width: <?=(pow(2,$max_level))*300?>px;
        <?}?>

        overflow: hidden;
    }

    .bord{
        width: 50%;
        height: 45px;
        border: 1px solid;
        border-bottom: none;
        margin-left: 25%;
        margin-top: -10px;
    }
    <?
        for($i=1;$i<=$max_level;$i++){?>
            .line<?=$i?> .item-block{
                width: <?=(100/pow(2,$i))?>%;
                float: left;
            }
        <?}
    ?>
    .main{
        overflow: scroll;
        padding-bottom: 120px;
        background-color: cyan;
        border-radius: 10px;
    }
    #main{
        zoom: 1;
    }
</style>
<div class="site-index">

    <div id="filters" style="margin-left: 30px">
        <div class="row">
            <div class="col-xs-3">
                <form id="w0" action="/site/mat" method="get">

                    <div class="form-group field-activities-username">
                        <label class="control-label" for="activities-username">Площадка</label>
                        <select name="level" id="level" class="form-control">
                            <option value="1" <?if($level == 1){?>selected<?}?>>Уровень 1</option>
                            <option value="2" <?if($level == 2){?>selected<?}?>>Уровень 2</option>
                            <option value="3" <?if($level == 3){?>selected<?}?>>Уровень 3</option>
                            <option value="4" <?if($level == 4){?>selected<?}?>>Уровень 4</option>
                            <option value="5" <?if($level == 5){?>selected<?}?>>Уровень 5</option>
                            <option value="6" <?if($level == 6){?>selected<?}?>>Уровень 6</option>
                        </select>
                        <div class="help-block"></div>
                    </div>
                    <div class="form-group field-activities-username">
                        <label class="control-label" for="activities-username">Логин</label>
                        <?
                        echo TypeaheadBasic::widget([
                            'name' => 'username',
                            'data' =>  $data,
                            'value'=>$username,
                            'options' => ['placeholder' => 'Введите логин ...','id'=>'username','class'=>'form-control','autocomplete'=>'off'],
                            'pluginOptions' => ['highlight'=>true],
                        ]);
                        ?>

                        <div class="help-block"><?=$error?></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Поиск</button>    </div>

                </form>
            </div>
        </div>
    </div>
        <div id="mas" style="margin-left: 30px">
            <div class="row">
                <div class="col-3">
                    <button class="btn btn-primary" id="upscrl">+</button>
                </div>
                <div class="col-3">
                    <button class="btn btn-primary" id="downscrl">-</button>
                </div>
            </div>
        </div>

    <div class="main" id="main">
        <?if(!empty($mat['parent_id'])){
            $p_mat = MatrixRef::findOne($mat['parent_id']);
            $p_user = User::findOne($p_mat['user_id']);
            ?>
            <div class="line">
                <div class="item-block">
                    <div class="item">
                    <span class="mat-info <? if($p_user['id'] == $main_user_id){echo "mine";} ?>">
                        <? if ($p_mat['reinvest'] == 1) {?>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                            </svg>
                        <?}elseif ($p_mat['buy'] == 1){?>
                            <i class="fa fa-dollar"></i>
                        <?}else{?>
                            <i class="fa fa-dashboard"></i>
                        <?}?>
                        <a href="/users/view?id=<?=$p_user['id']?>" target="_blank"><?=$p_user['username']?></a>
                        <?
                        $p_num1 = \common\models\MatClons::find()->where(['mat_id'=>$p_mat['id']])->one();
                        if(!empty($p_num1)){?>
                            (<?=$p_num1['num']?>)
                        <?}
                        ?>
                        <a href="/site/mat?mat=<?=$p_mat['id']?>" target="_blank">[<?=$p_mat['id']?>]</a>

                    </span>
                    </div>
                </div>

            </div>

        <?}?>
        <div class="line">
            <div class="item-block">
                <div class="item">
                    <span class="mat-info <? if($user['id'] == $main_user_id){echo "mine";} ?>">
                        <? if ($mat['reinvest'] == 1) {?>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                            </svg>
                        <?}elseif ($mat['buy'] == 1){?>
                            <i class="fa fa-dollar"></i>
                        <?}else{?>
                            <i class="fa fa-dashboard"></i>
                        <?}?>
                        <a href="/users/view?id=<?=$user['id']?>" target="_blank"><?=$user['username']?></a>
                        <?
                        $num1 = \common\models\MatClons::find()->where(['mat_id'=>$mat['id']])->one();
                        if(!empty($num1)){?>
                            (<?=$num1['num']?>)
                        <?}
                        ?>
                        [<?=$mat['id']?>]
                    </span>
                </div>
                <?
                if(count($array_list)>0){?>
                    <div class="bord">
                    </div>
                <?}?>

            </div>

        </div>
        <?
            for($i=1;$i<=$max_level;$i++){
                $count = pow(2,$i);
        ?>
                <div class="line line<?=$i?>">
                    <?
                        for($k=1;$k<=$count;$k++){
                            $ch_id = null;
                            if(isset($array_list[$i][$k])){
                                $ch_id = $array_list[$i][$k];
                                $mat = MatrixRef::findOne($ch_id);
                                $user = User::findOne($mat['user_id']);

                            }
                    ?>
                            <div class="item-block">
                                <div class="item <?if(empty($ch_id)){echo "empty";}?>">
                                    <?if(!empty($ch_id)){?>
                                        <span class="mat-info <? if($user['id'] == $main_user_id){echo "mine";} ?>">
                                            <? if ($mat['reinvest'] == 1) {?>
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-files" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M4 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4z"/>
                                                    <path d="M6 0h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1H4a2 2 0 0 1 2-2z"/>
                                                </svg>
                                            <?}elseif ($mat['buy'] == 1){?>
                                                <i class="fa fa-dollar"></i>
                                            <?}else{?>
                                                <i class="fa fa-dashboard"></i>
                                            <?}?>
                                                <a href="/users/view?id=<?=$user['id']?>" target="_blank"><?=$user['username']?></a>

                                                <?
                                                $num1 = \common\models\MatClons::find()->where(['mat_id'=>$mat['id']])->one();
                                                if(!empty($num1)){?>
                                                    (<?=$num1['num']?>)
                                                <?}
                                                ?>
                                            <a href="/site/mat?mat=<?=$mat['id']?>" target="_blank">[<?=$mat['id']?>]</a>
                                        </span>
                                    <?}?>
                                </div>
                                <?
                                    if($max_level != $i){?>
                                        <div class="bord">
                                        </div>
                                    <?}?>

                            </div>
                        <?}?>
                </div>
        <?}?>
    </div>
</div>
