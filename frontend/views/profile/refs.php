<?php

use yii\httpclient\Client;


$this->title = "Промоушен";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}

if($user['level']>0){
    $children1 = \common\models\User::find()->where(['parent_id'=>$user['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
}

 ?>
    <style>
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
        .empty-mat{
            background: #d4d4d5!important;
        }
        .empty-mat svg{
            background: #d4d4d5!important;
        }
        .avatar-block {
            min-width: 50%;
        }
        .avatar-block .avatar-icon {
            padding: .2rem;
        }
        .avatar-block .avatar-block-wrap {
            padding-left: .3rem
        }
        .avatar-block .avatar-block-wrap .h4 {
            font-size: .875rem!important;
        }
    </style>
    <main class="cours">
        <div class="container-fluid">
            <div class="row">


                <main role="main" class="structure col-md-12">

                    <div class="structure-wrap" style="width: 4500px">
                        <div class="d-flex justify-content-center hgroup">
                            <h1 class="h1">Промоушен</h1>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4 offset-4">
                                        <div class="avatar-block mb-3">
                                            <div class="avatar-icon">
                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
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
                            <div class="col-12">
                                <div class="row">

                                        <div class="col-4 offset-2">
                                            <div class="avatar-block mb-3">
                                                <div class="avatar-icon <?if(!isset($children1[0])){?>empty-mat<?}?>">
                                                    <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <h4 class="h4 mb-0"><?if(isset($children1[0])){?><?=$children1[0]['username']?><?}?></h4>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-4">
                                            <div class="avatar-block mb-3">
                                                <div class="avatar-icon <?if(!isset($children1[1])){?>empty-mat<?}?>" >
                                                    <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <h4 class="h4 mb-0"><?if(isset($children1[1])){?><?=$children1[1]['username']?><?}?></h4>
                                                </div>
                                            </div>
                                        </div>


                                </div>
                            </div>
                            <div class="col-12">
                                    <div class="row">
                                        <?
                                        if(isset($children1[0])){
                                            $children2_1 = \common\models\User::find()->where(['parent_id'=>$children1[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                        }
                                        if(isset($children1[0])){
                                            $children2_2 = \common\models\User::find()->where(['parent_id'=>$children1[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                        }
                                        ?>
                                        <div class="col-3">
                                            <div class="avatar-block mb-3">
                                                <div class="avatar-icon <?if(!isset($children2_1[0])){?>empty-mat<?}?>">
                                                    <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <h4 class="h4 mb-0"><?if(isset($children2_1[0])){?><?=$children2_1[0]['username']?><?}?></h4>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-3">
                                            <div class="avatar-block mb-3">
                                                <div class="avatar-icon <?if(!isset($children2_1[1])){?>empty-mat<?}?>" >
                                                    <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <h4 class="h4 mb-0"><?if(isset($children2_1[1])){?><?=$children2_1[1]['username']?><?}?></h4>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-3">
                                            <div class="avatar-block mb-3">
                                                <div class="avatar-icon <?if(!isset($children2_2[0])){?>empty-mat<?}?>" >
                                                    <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <h4 class="h4 mb-0"><?if(isset($children2_2[0])){?><?=$children2_2[0]['username']?><?}?></h4>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-3">
                                            <div class="avatar-block mb-3">
                                                <div class="avatar-icon <?if(!isset($children2_2[1])){?>empty-mat<?}?>">
                                                    <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                    </svg>
                                                </div>

                                                <div class="avatar-block-wrap">
                                                    <h4 class="h4 mb-0"><?if(isset($children2_2[1])){?><?=$children2_2[1]['username']?><?}?></h4>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            <div class="col-12">
                                <div class="row">
                                    <?
                                    if(isset($children2_1[0])){
                                        $children3_1 = \common\models\User::find()->where(['parent_id'=>$children2_1[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children2_1[1])){
                                        $children3_2 = \common\models\User::find()->where(['parent_id'=>$children2_1[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children2_2[0])){
                                        $children3_3 = \common\models\User::find()->where(['parent_id'=>$children2_2[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children2_2[1])){
                                        $children3_4 = \common\models\User::find()->where(['parent_id'=>$children2_2[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    ?>

                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_1[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_1[0])){?><?=$children3_1[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_1[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_1[1])){?><?=$children3_1[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_2[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_2[0])){?><?=$children3_2[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_2[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_2[1])){?><?=$children3_2[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_3[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_3[0])){?><?=$children3_3[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_3[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_3[1])){?><?=$children3_3[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_4[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_4[0])){?><?=$children3_4[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children3_4[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children3_4[1])){?><?=$children3_4[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <?
                                    if(isset($children3_1[0])){
                                        $children4_1 = \common\models\User::find()->where(['parent_id'=>$children3_1[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children3_1[1])){
                                        $children4_2 = \common\models\User::find()->where(['parent_id'=>$children3_1[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children3_2[0])){
                                        $children4_3 = \common\models\User::find()->where(['parent_id'=>$children3_2[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children3_2[1])){
                                        $children4_4 = \common\models\User::find()->where(['parent_id'=>$children3_2[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children3_3[0])){
                                        $children4_5 = \common\models\User::find()->where(['parent_id'=>$children3_3[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children3_3[1])){
                                        $children4_6 = \common\models\User::find()->where(['parent_id'=>$children3_3[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children3_4[0])){
                                        $children4_7 = \common\models\User::find()->where(['parent_id'=>$children3_4[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children3_4[1])){
                                        $children4_8 = \common\models\User::find()->where(['parent_id'=>$children3_4[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    ?>

                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_1[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_1[0])){?><?=$children4_1[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_1[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_1[1])){?><?=$children4_1[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_2[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_2[0])){?><?=$children4_2[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_2[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_2[1])){?><?=$children4_2[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_3[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_3[0])){?><?=$children4_3[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_3[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_3[1])){?><?=$children4_3[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_4[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_4[0])){?><?=$children4_4[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_4[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_4[1])){?><?=$children4_4[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_5[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_5[0])){?><?=$children4_5[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_5[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_5[1])){?><?=$children4_5[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_6[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_6[0])){?><?=$children4_6[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_6[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_6[1])){?><?=$children4_6[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_7[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_7[0])){?><?=$children4_7[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_7[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_7[1])){?><?=$children4_7[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_8[0])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_8[0])){?><?=$children4_8[0]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="avatar-block mb-3">
                                                    <div class="avatar-icon <?if(!isset($children4_8[1])){?>empty-mat<?}?>">
                                                        <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="avatar-block-wrap">
                                                        <h4 class="h4 mb-0"><?if(isset($children4_8[1])){?><?=$children4_8[1]['username']?><?}?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <?
                                    if(isset($children4_1[0])){
                                        $children5_1 = \common\models\User::find()->where(['parent_id'=>$children4_1[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_1[1])){
                                        $children5_2 = \common\models\User::find()->where(['parent_id'=>$children4_1[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children4_2[0])){
                                        $children5_3 = \common\models\User::find()->where(['parent_id'=>$children4_2[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_2[1])){
                                        $children5_4 = \common\models\User::find()->where(['parent_id'=>$children4_2[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children4_3[0])){
                                        $children5_5 = \common\models\User::find()->where(['parent_id'=>$children4_3[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_3[1])){
                                        $children5_6 = \common\models\User::find()->where(['parent_id'=>$children4_3[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children4_4[0])){
                                        $children5_7 = \common\models\User::find()->where(['parent_id'=>$children4_4[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_4[1])){
                                        $children5_8 = \common\models\User::find()->where(['parent_id'=>$children4_4[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }


                                    if(isset($children4_5[0])){
                                        $children5_9 = \common\models\User::find()->where(['parent_id'=>$children4_5[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_5[1])){
                                        $children5_10 = \common\models\User::find()->where(['parent_id'=>$children4_5[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children4_6[0])){
                                        $children5_11 = \common\models\User::find()->where(['parent_id'=>$children4_6[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_6[1])){
                                        $children5_12 = \common\models\User::find()->where(['parent_id'=>$children4_6[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children4_7[0])){
                                        $children5_13 = \common\models\User::find()->where(['parent_id'=>$children4_7[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_7[1])){
                                        $children5_14 = \common\models\User::find()->where(['parent_id'=>$children4_7[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }

                                    if(isset($children4_8[0])){
                                        $children5_15 = \common\models\User::find()->where(['parent_id'=>$children4_8[0]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    if(isset($children4_8[1])){
                                        $children5_16 = \common\models\User::find()->where(['parent_id'=>$children4_8[1]['id']])->andWhere(['>','platform_id',0])->orderBy('level desc')->all();
                                    }
                                    ?>

                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_1[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_1[0])){?><?=$children5_1[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_1[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_1[1])){?><?=$children5_1[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_2[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_2[0])){?><?=$children5_2[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_2[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_2[1])){?><?=$children5_2[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_3[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_3[0])){?><?=$children5_3[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_3[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_3[1])){?><?=$children5_3[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_4[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_4[0])){?><?=$children5_4[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_4[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_4[1])){?><?=$children5_4[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_5[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_5[0])){?><?=$children5_5[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_5[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_5[1])){?><?=$children5_5[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_6[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_6[0])){?><?=$children5_6[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_6[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_6[1])){?><?=$children5_6[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_7[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_7[0])){?><?=$children5_7[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_7[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_7[1])){?><?=$children5_7[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_8[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_8[0])){?><?=$children5_8[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_8[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_8[1])){?><?=$children5_8[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_9[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_9[0])){?><?=$children5_9[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_9[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_9[1])){?><?=$children5_9[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_10[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_10[0])){?><?=$children5_10[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_10[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_10[1])){?><?=$children5_10[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_11[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_11[0])){?><?=$children5_11[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_11[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_11[1])){?><?=$children5_11[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_12[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_12[0])){?><?=$children5_12[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_12[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_12[1])){?><?=$children5_12[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_13[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_13[0])){?><?=$children5_13[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_13[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_13[1])){?><?=$children5_13[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_14[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_14[0])){?><?=$children5_14[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_14[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_14[1])){?><?=$children5_14[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_15[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_15[0])){?><?=$children5_15[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_15[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_15[1])){?><?=$children5_15[1]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_16[0])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_16[0])){?><?=$children5_16[0]['username']?><?}?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="avatar-block mb-3">
                                                            <div class="avatar-icon <?if(!isset($children5_16[1])){?>empty-mat<?}?>">
                                                                <svg class="bi bi-person-fill avatar-icon" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                                </svg>
                                                            </div>

                                                            <div class="avatar-block-wrap">
                                                                <h4 class="h4 mb-0"><?if(isset($children5_16[1])){?><?=$children5_16[1]['username']?><?}?></h4>
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
            </div>
        </div>



    </main>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>