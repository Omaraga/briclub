<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\UsersSearchFront */
/* @var $user common\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $is_child boolean */

use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = "Основатели";

$columns = [
    'username',
    'email:email',
    'fio',
    'phone',
    [
        'attribute'=>'parentName',
        'label' => 'Наставник',
        'value'=>'parent.username',
    ],
    [
        'attribute'=>'Система',
        'content'=>function($data){
            $res = "";

            if($data['newmatrix'] == 1){
                $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
                $res = "Уровень ".$level['platform_id'];
            }else{
                $res = "Не активирован";
            }

            return $res;
        }
    ],
    [
        'contentOptions' => ['class' => 'mobile'],
        'headerOptions' => ['class' => 'mobile'],
        'content'=>function($data){
            $parentUser = \common\models\User::findOne($data['parent_id']);
            $level = \common\models\MatrixRef::find()->where(['user_id'=>$data['id']])->orderBy('platform_id desc')->one();
            $dataJson = ['fio'=> $data['fio'], 'parent'=> $parentUser['username'], 'email'=>$data['email'],'level'=>$level['platform_id'], 'created_at'=>date('d.m.Y H:i',$data['created_at']), 'time_personal'=>date('d.m.Y H:i',$data['time_personal'])];
            $res = "<div class='mobile-item' data-json='".htmlspecialchars(json_encode($dataJson), ENT_QUOTES, 'UTF-8')."'><span class='mt-2 d-block w5'>"."<span class='myRefsUsername'>".$data['username']."</span>"." / ".$data['fio']."</span>"."<span class='mb-2'>".$data['phone']."</span></div>";
            return $res;
        }
    ],
    ['attribute' => 'created_at', 'format' => ['date', 'php:d.m.Y H:i']],
    ['attribute' => 'time_personal', 'format' => ['date', 'php:d.m.Y H:i']],
];

$this->registerJsFile('/js/referal.js',['depends'=>'yii\web\JqueryAsset']);

?>
    <style>
        .mobile-info-block{
            width: 100%;
            height: 40vh;
            position: fixed;
            z-index: 100;
            bottom: 0;
            left: 0;
            background-color: white;
            padding: 1rem 2rem;
            box-shadow: 2px 0px 14px 6px rgba(34, 60, 80, 0.2);
            display: none;
            border-radius: 1.5rem 1.5rem 0rem 0rem ;
            color: #000;
        }
        .mobile-item{
            cursor:pointer;
        }
        .mobile-item span{
            color: #fff;
        }
        thead th{
            border-left: none!important;
            border-right: none!important;
            border-top: none!important;
            border-bottom: 1px solid #263554!important;
            text-align: center;
        }
        tbody td{
            border-left: none!important;
            border-right: none!important;
            border-top: none!important;
            border-bottom: 1px solid #263554!important;
            vertical-align: middle;
            text-align: center;
        }
        thead th a{
            color: #fff;
        }
        tbody td a{
            color: #fff;
        }
        tbody tr:hover{
            background: #263554;
            border-radius: 4px 4px 0px 0px;
        }
        .table-bordered{
            border: none;
            background: #0F182A;
            border-radius: 4px;
            color: #fff;
        }
        #myReferal td.mobile, thead .mobile{
            display: none;
            text-align: left;
        }
        .mobile-info-block{
            background: #263554;
            color: #fff;
        }
        .content p{
            margin-bottom: 8px;
        }
        .form-group input{
            background: #0F182B;
            border: 1px solid #192233;
            border-radius: 4px;
            color: #fff!important;
        }
        .form-group input:focus{
            background: #0F182B;
        }
        .form-group select{
            background: #0F182B;
            border: 1px solid #192233;
            border-radius: 4px;
            color: #fff;
        }
        .form-group select:focus{
            background: #0F182B;
        }
        select option{
            color: #fff;
        }
        #ui-datepicker-div{
            background: #0F182B;
            color: #fff;
            border: 1px solid #263554!important;
        }
        .ui-datepicker-calendar td a{
            background: #263554!important;
            color: #fff!important;
            border: 1px solid #263554!important;
        }
        .ui-datepicker-header{
            background: #263554;
            color: #fff;
            border: 1px solid #263554!important;
        }
        .form-group a{
            color: #fff;
        }.form-group{
            margin-bottom: 1.5rem;
        }
        .summary{
            margin-bottom: 24px;
        }
        .pagination a{
            background: #263554!important;
            border: 1px solid #263554!important;
            color: #fff!important;
        }
        .pagination .disabled span{
            background: #263554!important;
            border: 1px solid #263554!important;
            color: #fff!important;
        }
        .pagination .active a{
            background: #007bff!important;
        }
        @media screen and (max-width: 575px){
            #myReferal td, #myReferal th {
                display: none;
                border: none;
                margin-top: 0.5rem;
                background: #0F182A;
                border-radius: 4px;
            }
            #myReferal td:hover{
                background: #263554;
            }
            #myReferal td.mobile {
                display: block;
                color: #747474;
            }
        }
    </style>
    <main class="cours" id="myReferal">
        <div>
            <div>
                <div class="d-flex justify-content-between flex-column flex-wrap align-items-start hgroup">
                    <h1 class="h1 mb-5">Основатели <?if($is_child){echo $user['username'];}?></h1>
                    <?$parent = $user->getParent();?>
                    <?if($parent):?>
                        <p>Наставник: <?=$parent['username']?></p>
                    <?endif;?>
                    <p class="mb-4">Всего в системе основателей: <?=($user->getMembersQuantity())?></p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php Pjax::begin(); ?>
                        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $columns,
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="mobile-info-block">
            <h4 id="refs-name" style="margin-bottom: 1.5rem">ФИО</h4>
            <div class="content">
                <p><i class="fa fa-envelope"></i><span id="refs-email" class="ml-2">  email@mail.ru</span></p>
                <p><i class="fa fa-child mr-2"></i>Наставник: <span id="refs-parent" class=""></span></p>
                <p ><i class="fas fa-network-wired"></i><span id="refs-level" class="ml-2">1</span></p>
                <p ><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Дата регистрации:<span class="ml-2" id="refs-date"></span></p>
                <p ><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Дата активации:<span class="ml-2" id="refs-date-activate"></span></p>
            </div>
        </div>
    </main>
