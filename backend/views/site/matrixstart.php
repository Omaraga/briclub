<?php

/* @var $this yii\web\View */

$this->title = 'Матрица';

use yii\web\View; ?>
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
        <h4>Platform 1 </h4>
        <? foreach ($matrix1 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?>
                <?
                if($mat['reinvest'] == 1){
                    echo "REINVEST";
                }elseif ($mat['clone'] == 1){
                    echo "CLONE";
                }
                ?>
                w: <?=\common\models\User::findOne($mat['user_id'])['w_balans']?></div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\MatrixStart::find()->where(['parent_id'=>$mat['id']])->all();
            }

            ?>
            <div class="row">

                <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1">
                    <?if(isset($children1[0])){?>
                        <?=\common\models\User::findOne($children1[0]['user_id'])['username']?>
                        <?
                        $matr = $children1[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2">
                    <?if(isset($children1[1])){?>
                        <?=\common\models\User::findOne($children1[1]['user_id'])['username']?>
                        <?
                        $matr = $children1[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[0]['id']])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[1]['id']])->all();
            }

            ?>
            <div class="row">



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[0])){?>
                        <?=\common\models\User::findOne($children2[0]['user_id'])['username']?>
                        <?
                        $matr = $children2[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[1])){?>
                        <?=\common\models\User::findOne($children2[1]['user_id'])['username']?>
                        <?
                        $matr = $children2[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[0])){?>
                        <?=\common\models\User::findOne($children3[0]['user_id'])['username']?>
                        <?
                        $matr = $children3[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[1])){?>
                        <?=\common\models\User::findOne($children3[1]['user_id'])['username']?>
                        <?
                        $matr = $children3[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 2 </h4>
        <? foreach ($matrix2 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?>
                <?
                if($mat['reinvest'] == 1){
                    echo "REINVEST";
                }elseif ($mat['clone'] == 1){
                    echo "CLONE";
                }
                ?>
                w: <?=\common\models\User::findOne($mat['user_id'])['w_balans']?></div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\MatrixStart::find()->where(['parent_id'=>$mat['id']])->all();
            }

            ?>
            <div class="row">

                <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1">
                    <?if(isset($children1[0])){?>
                        <?=\common\models\User::findOne($children1[0]['user_id'])['username']?>
                        <?
                        $matr = $children1[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2">
                    <?if(isset($children1[1])){?>
                        <?=\common\models\User::findOne($children1[1]['user_id'])['username']?>
                        <?
                        $matr = $children1[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[0]['id']])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[1]['id']])->all();
            }

            ?>
            <div class="row">



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[0])){?>
                        <?=\common\models\User::findOne($children2[0]['user_id'])['username']?>
                        <?
                        $matr = $children2[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[1])){?>
                        <?=\common\models\User::findOne($children2[1]['user_id'])['username']?>
                        <?
                        $matr = $children2[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[0])){?>
                        <?=\common\models\User::findOne($children3[0]['user_id'])['username']?>
                        <?
                        $matr = $children3[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[1])){?>
                        <?=\common\models\User::findOne($children3[1]['user_id'])['username']?>
                        <?
                        $matr = $children3[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 3 </h4>
        <? foreach ($matrix3 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?>
                <?
                if($mat['reinvest'] == 1){
                    echo "REINVEST";
                }elseif ($mat['clone'] == 1){
                    echo "CLONE";
                }
                ?>
                w: <?=\common\models\User::findOne($mat['user_id'])['w_balans']?></div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\MatrixStart::find()->where(['parent_id'=>$mat['id']])->all();
            }

            ?>
            <div class="row">

                <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1">
                    <?if(isset($children1[0])){?>
                        <?=\common\models\User::findOne($children1[0]['user_id'])['username']?>
                        <?
                        $matr = $children1[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2">
                    <?if(isset($children1[1])){?>
                        <?=\common\models\User::findOne($children1[1]['user_id'])['username']?>
                        <?
                        $matr = $children1[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[0]['id']])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[1]['id']])->all();
            }

            ?>
            <div class="row">



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[0])){?>
                        <?=\common\models\User::findOne($children2[0]['user_id'])['username']?>
                        <?
                        $matr = $children2[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[1])){?>
                        <?=\common\models\User::findOne($children2[1]['user_id'])['username']?>
                        <?
                        $matr = $children2[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[0])){?>
                        <?=\common\models\User::findOne($children3[0]['user_id'])['username']?>
                        <?
                        $matr = $children3[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[1])){?>
                        <?=\common\models\User::findOne($children3[1]['user_id'])['username']?>
                        <?
                        $matr = $children3[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>
    <div>
        <h4>Platform 4 </h4>
        <? foreach ($matrix4 as $mat) {?>
            <div style="margin-left: 140px;" class="col-lg-12 first-block"><?=\common\models\User::findOne($mat['user_id'])['username']?>
                <?
                if($mat['reinvest'] == 1){
                    echo "REINVEST";
                }elseif ($mat['clone'] == 1){
                    echo "CLONE";
                }
                ?>
                w: <?=\common\models\User::findOne($mat['user_id'])['w_balans']?></div>
            <?
            $children1 = null;$children2 = null;$children3 = null;
            if($mat['children']>0){
                $children1 = \common\models\MatrixStart::find()->where(['parent_id'=>$mat['id']])->all();
            }

            ?>
            <div class="row">

                <div style="margin-left: 60px;width: 180px;float: left;" class="col-lg-12 second-block1">
                    <?if(isset($children1[0])){?>
                        <?=\common\models\User::findOne($children1[0]['user_id'])['username']?>
                        <?
                        $matr = $children1[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 20px;width: 200px;" class="col-lg-12 second-block2">
                    <?if(isset($children1[1])){?>
                        <?=\common\models\User::findOne($children1[1]['user_id'])['username']?>
                        <?
                        $matr = $children1[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children1[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <?
            if(isset($children1[0]['children']) and $children1[0]['children']>0){
                $children2 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[0]['id']])->all();
            }
            if(isset($children1[1]['children']) and $children1[1]['children']>0){
                $children3 = \common\models\MatrixStart::find()->where(['parent_id'=>$children1[1]['id']])->all();
            }

            ?>
            <div class="row">



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[0])){?>
                        <?=\common\models\User::findOne($children2[0]['user_id'])['username']?>
                        <?
                        $matr = $children2[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children2[1])){?>
                        <?=\common\models\User::findOne($children2[1]['user_id'])['username']?>
                        <?
                        $matr = $children2[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children2[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>



                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[0])){?>
                        <?=\common\models\User::findOne($children3[0]['user_id'])['username']?>
                        <?
                        $matr = $children3[0];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[0]['user_id'])['w_balans']?>
                    <?}?>
                </div>


                <div style="margin-left: 0px;width: 110px;" class="col-lg-12 third-block">
                    <?if(isset($children3[1])){?>
                        <?=\common\models\User::findOne($children3[1]['user_id'])['username']?>
                        <?
                        $matr = $children3[1];
                        if($matr['reinvest'] == 1){
                            echo "REINVEST";
                        }elseif ($matr['clone'] == 1){
                            echo "CLONE";
                        }
                        ?>
                        w: <?=\common\models\User::findOne($children3[1]['user_id'])['w_balans']?>
                    <?}?>
                </div>

            </div>
            <div class="row">
                <hr>
            </div>
        <?}?>
    </div>

</div>
