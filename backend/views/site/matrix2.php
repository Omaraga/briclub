<?php

/* @var $this yii\web\View */

$this->title = 'Матрица';
?>
<style>
    .first-block{
        margin-left: 360px!important;
        margin-top: 30px!important;
    }
    .second-block1{
        margin-left: 130px!important;
        width: 250px!important;
        margin-top: 30px!important;
    }
    .second-block2{
        margin-left: 235px!important;
        width: 250px!important;
        margin-top: 30px!important;
    }
    .third-block{
        margin-left: 0px!important;
        width: 250px!important;
        margin-top: 30px!important;
    }
</style>
<div class="site-index">

        <div>
            <h4>Platform 0 -- <?=count($matrix);?></h4>

            <? foreach ($matrix as $mat) {?>
                <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?> w: <?=\common\models\User::findOne($mat['user_id'])['id']?> </div>
                <?
                    $children1 = null;$children2 = null;$children3 = null;
                    if($mat['children']>0){
                        $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
                    }

                ?>
                <div class="row">
                <?if(isset($children1[0])){?>
                    <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1"><?=\common\models\User::findOne($children1[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[0]['user_id'])['id']?></div>
                <?}?>

                <?if(isset($children1[1])){?>
                    <div style="margin-left: 20px;" class="col-lg-12 second-block2"><?=\common\models\User::findOne($children1[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[1]['user_id'])['id']?></div>
                <?}?>
                </div>
                <div class="row">
                    <hr>
                </div>

            <?}?>
        </div>
    <div>
        <h4>Platform 1 </h4>
        <? foreach ($matrix1 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?> w: <?=\common\models\User::findOne($mat['user_id'])['id']?></div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
            }

            ?>
        <div class="row">
            <?if(isset($children1[0])){?>
                <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1"><?=\common\models\User::findOne($children1[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[0]['user_id'])['id']?> </div>
            <?}?>

            <?if(isset($children1[1])){?>
                <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2"><?=\common\models\User::findOne($children1[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[1]['user_id'])['id']?></div>
            <?}?>
        </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
            }

            ?>
            <div class="row">


                <?if(isset($children2[0])){?>
                    <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[0]['user_id'])['id']?> </div>
                <?}?>
                <?if(isset($children2[1])){?>
                    <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[1]['user_id'])['id']?> </div>
                <?}?>

                <?if(isset($children3[0])){?>
                    <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[0]['user_id'])['username']?>w: <?=\common\models\User::findOne($children3[0]['user_id'])['id']?> </div>
                <?}?>
                <?if(isset($children3[1])){?>
                    <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[1]['user_id'])['id']?> </div>
                <?}?>
            </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 2 </h4>
        <? foreach ($matrix2 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?> w: <?=\common\models\User::findOne($mat['user_id'])['id']?></div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
            }

            ?>
            <div class="row">
                <?if(isset($children1[0])){?>
                    <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1"><?=\common\models\User::findOne($children1[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[0]['user_id'])['id']?> </div>
                <?}?>

                <?if(isset($children1[1])){?>
                    <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2"><?=\common\models\User::findOne($children1[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[1]['user_id'])['id']?> </div>
                <?}?>
            </div>
        <div class="row">
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
            }

            ?>
            <?if(isset($children2[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children2[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[1]['user_id'])['id']?> </div>
            <?}?>

            <?if(isset($children3[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children3[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[1]['user_id'])['id']?> </div>
            <?}?>
        </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 3 </h4>
        <? foreach ($matrix3 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?> w: <?=\common\models\User::findOne($mat['user_id'])['id']?> </div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
            }

            ?>
            <div class="row">
                <?if(isset($children1[0])){?>
                    <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1"><?=\common\models\User::findOne($children1[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[0]['user_id'])['id']?> </div>
                <?}?>

                <?if(isset($children1[1])){?>
                    <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2"><?=\common\models\User::findOne($children1[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[1]['user_id'])['id']?> </div>
                <?}?>
            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
            }

            ?>
        <div class="row">
            <?if(isset($children2[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children2[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[1]['user_id'])['id']?> </div>
            <?}?>

            <?if(isset($children3[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children3[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[1]['user_id'])['id']?> </div>
            <?}?>
        </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 4 </h4>
        <? foreach ($matrix4 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?> w: <?=\common\models\User::findOne($mat['user_id'])['id']?> </div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
            }

            ?>
            <div class="row">
                <?if(isset($children1[0])){?>
                    <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1"><?=\common\models\User::findOne($children1[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[0]['user_id'])['id']?> </div>
                <?}?>

                <?if(isset($children1[1])){?>
                    <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2"><?=\common\models\User::findOne($children1[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[1]['user_id'])['id']?> </div>
                <?}?>
            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
            }

            ?>
        <div class="row">
            <?if(isset($children2[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children2[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[1]['user_id'])['id']?> </div>
            <?}?>

            <?if(isset($children3[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children3[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[1]['user_id'])['id']?></div>
            <?}?>
        </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 5 </h4>
        <? foreach ($matrix5 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?> w: <?=\common\models\User::findOne($mat['user_id'])['id']?> </div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
            }

            ?>
            <div class="row">
                <?if(isset($children1[0])){?>
                    <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1"><?=\common\models\User::findOne($children1[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[0]['user_id'])['id']?> </div>
                <?}?>

                <?if(isset($children1[1])){?>
                    <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2"><?=\common\models\User::findOne($children1[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children1[1]['user_id'])['id']?> </div>
                <?}?>
            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
            }

            ?>
        <div class="row">
            <?if(isset($children2[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children2[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children2[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children2[1]['user_id'])['id']?> </div>
            <?}?>

            <?if(isset($children3[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[0]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[0]['user_id'])['id']?> </div>
            <?}?>
            <?if(isset($children3[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block"><?=\common\models\User::findOne($children3[1]['user_id'])['username']?> w: <?=\common\models\User::findOne($children3[1]['user_id'])['id']?> </div>
            <?}?>
        </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 6 </h4>
        <? foreach ($matrix6 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?> $: <?=\common\models\User::findOne($mat['user_id'])['balans']?></div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
            }

            ?>
            <div class="row">
                <?if(isset($children1[0])){?>
                    <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1">User<?=\common\models\User::findOne($children1[0]['user_id'])['username']?> </div>
                <?}?>

                <?if(isset($children1[1])){?>
                    <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2">User<?=\common\models\User::findOne($children1[1]['user_id'])['username']?> </div>
                <?}?>
            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
            }

            ?>
        <div class="row">
            <?if(isset($children2[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children2[0]['user_id'])['username']?> </div>
            <?}?>
            <?if(isset($children2[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children2[1]['user_id'])['username']?> </div>
            <?}?>

            <?if(isset($children3[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children3[0]['user_id'])['username']?> </div>
            <?}?>
            <?if(isset($children3[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children3[1]['user_id'])['username']?></div>
            <?}?>
        </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 7</h4>
        <? foreach ($matrix7 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block">User<?=\common\models\User::findOne($mat['user_id'])['username']?> </div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$mat['id'],'deleted'=>2])->all();
            }

            ?>
            <div class="row">
                <?if(isset($children1[0])){?>
                    <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1">User<?=\common\models\User::findOne($children1[0]['user_id'])['username']?> </div>
                <?}?>

                <?if(isset($children1[1])){?>
                    <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2">User<?=\common\models\User::findOne($children1[1]['user_id'])['username']?> </div>
                <?}?>
            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[0]['id'],'deleted'=>2])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\UserPlatforms2::find()->where(['parent_id'=>$children1[1]['id'],'deleted'=>2])->all();
            }

            ?>
        <div class="row">
            <?if(isset($children2[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children2[0]['user_id'])['username']?> </div>
            <?}?>
            <?if(isset($children2[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children2[1]['user_id'])['username']?> </div>
            <?}?>

            <?if(isset($children3[0])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children3[0]['user_id'])['username']?> </div>
            <?}?>
            <?if(isset($children3[1])){?>
                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">User<?=\common\models\User::findOne($children3[1]['user_id'])['username']?> </div>
            <?}?>
        </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
</div>
