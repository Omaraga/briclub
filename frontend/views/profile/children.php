<?php

/* @var $this yii\web\View */
use common\models\MatrixRef;
use common\models\UserPlatforms;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\httpclient\Client;
use yii\widgets\Pjax;


$this->title = "Личники";
//$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');

$children = \common\models\User::find()->where(['parent_id'=>$user['id']])->all();

$username = Yii::$app->user->identity['username'];
$activ = $user['activ'];

if($activ == 1){
    $activ = true;
    $url = 'http://'.$_SERVER['SERVER_NAME'].'/invite/'.$username;
}else{
    $activ = false;
}

$premium = \common\models\Premiums::findOne(['user_id' => Yii::$app->user->id]);
$parent = \common\models\User::findOne($user['parent_id']);
$start = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'activ'=>1])->andWhere(['>','time_start',0])->count();
$personal = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'activ'=>1])->andWhere(['>','time_personal',0])->count();
$global = \common\models\Referals::find()->where(['parent_id'=>$user['id'],'activ'=>1])->andWhere(['>','time_global',0])->count();

$plan = \common\models\UserPlans::find()->where(['user_id'=>$user['id']])->one();
$columns = [

    'username',
    'email:email',
    'fio',
    'phone',
    [
        'attribute'=>'parentName',
        'label' => 'Спонсор',
        'value'=>'parent.username',
    ],
    //'w_balans',
    /*[
        'attribute'=>'Токены',
        'content'=>function($data){
            $balans = \common\models\Tokens::find()->where(['user_id'=>$data['id']])->sum('balans');
            return $balans;
        }
    ],*/
    //['attribute' => 'countryName','label' => 'Страна','value'=>'country.title'],
    [
        'attribute'=>'Матрица',
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
    $this->registerJsFile('/js/clipboard.js');
    $this->registerJsFile('https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js');
    $this->registerJsFile('https://yastatic.net/share2/share.js');
    $this->registerJsFile('/js/referal.js',['depends'=>'yii\web\JqueryAsset']);
    $this->registerJs('
        let referalLink = $("#referalLink").text();
        $("#referalCheckbox").click(function (){
    
            if ($(this).is(":checked")){
                $("#referalLink").text(referalLink+"/1")
            }else{
                $("#referalLink").text(referalLink)
            }
        })
    ');

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
    </style>
    <main class="cours" id="myReferal">
        <div class="container">
            <div class="row">
                <?/*=\frontend\components\NavWidget::widget()*/?>
                    <div class="d-flex justify-content-between flex-column flex-wrap align-items-start hgroup">
                        <h1 class="h1">Личники <?if($is_child){echo $user['username'];}?></h1>
                        <?if(!empty($parent)){?>
                            <p>Спонсор: <?=$parent['username']?></p>
                        <?}?>
                        <?if($activ){?>
                            <button type="button" class="btn btn__blue btn__small my-2" data-toggle="modal" data-target="#staticBackdrop">
                                Пригласить в матрицу
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <span class="modal-title w5" id="staticBackdropLabel">Поделиться реферальной ссылкой</span>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                            <a class="referal-link" id="referalLink"><?=$url?></a>
                                            <div class="">
                                                <i class="fa fa-copy" aria-hidden="true"></i>
                                                <button class="btn btn-link" onclick="copy('referalLink')">Копировать</button>
                                            </div>
                                            </p>

                                            <div style="background: #FFD466; border-radius: 8px; margin: 2rem 0;">
                                                <div class="block__top-yallow" >
                                                    <img src="/img/main/Vector.svg" alt="">
                                                    <span class="w7 ml-2">Доступно для Premium аккаунта</span></div>
                                                <div class="block__yallow">
                                                    <input class="mr-2 main__input" type="checkbox" id="referalCheckbox" <?=($premium)?'':'disabled';?>>
                                                    <span>Поделиться статистикой доходов</span>
                                                </div>
                                            </div>

                                            <div class="btn__group">
                                                <p class="w5">Поделиться в:</p>
                                                <div class="ya-share2" data-title="Реферальная ссылка Shanyrakplus.com" data-url="<?=$url?>" data-services="vkontakte,facebook,twitter,viber,whatsapp,skype,telegram"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}?>
                        <p>Всего в структуре партнеров: <?=($personal)?></p>
                            
                        <?if(!empty($plan)){?>
                            <p>Награда за выполнение плана: <?=$plan['sum']?> US</p>
                        <?}?>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <?php Pjax::begin(); ?>
                            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>


                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                //'filterModel' => $searchModel,
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
                <p><i class="fas fa-envelope"></i><span id="refs-email" class="ml-2">  email@mail.ru</span></p>
                <p><i class="fas fa-child mr-2"></i>Спонсор: <span id="refs-parent" class=""></span></p>
                <p ><i class="fas fa-network-wired"></i><span id="refs-level" class="ml-2">1</span></p>
                <p ><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Дата регистрации:<span class="ml-2" id="refs-date"></span></p>
                <p ><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Дата активации:<span class="ml-2" id="refs-date-activate"></span></p>
            </div>
        </div>
    </main>
<?
echo \frontend\components\LoginWidget::widget();
?>