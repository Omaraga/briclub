<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Verifications */
use common\models\Verifications;
$user = \common\models\User::findOne($model->user_id);

$this->title = $user['username'];
$this->params['breadcrumbs'][] = ['label' => 'Верификация', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

function getVerifyStatusesAsArray($stage){
    switch ($stage){
        //-1 не начал 0 - на проверке или модификация 1 - прошел
        case Verifications::STAGE_SEND_EMAIL:
            return ['email'=>-1, 'main'=> -1, 'address'=> -1];
        case Verifications::STAGE_CHECK_EMAIL:
            return ['email'=>0, 'main'=> -1, 'address'=> -1];
        case Verifications::STAGE_READY_TO_VALID_USER_DATA:
            return ['email'=>1, 'main'=> -1, 'address'=> -1];
        case Verifications::STAGE_USER_DATA_WAIT_VALID:
            return ['email'=>1, 'main'=> 0, 'address'=> -1];
        case Verifications::STAGE_MODIFY_USER_DATA:
            return ['email'=>1, 'main'=> 0, 'address'=> -1];
        case Verifications::STAGE_READY_TO_VALID_ADDRESS:
            return ['email'=>1, 'main'=> 1, 'address'=> -1];
        case Verifications::STAGE_ADDRESS_WAIT_VALID:
            return ['email'=>1, 'main'=> 1, 'address'=> 0];
        case Verifications::STAGE_ADDRESS_MODIFY:
            return ['email'=>1, 'main'=> 1, 'address'=> 0];
        case Verifications::STAGE_ALL_VALIDATED:
            return ['email'=>1, 'main'=> 1, 'address'=> 1];
    }
}
function getArrayIcon($item){
    if ($item == -1){
        return'<span><i class="fa fa-circle-o-notch" aria-hidden="true" style="color:red;"></i> Не начато</span>';
    }else if($item == 0){
        return '<span><i class="fa fa-refresh" aria-hidden="true" style="color:green;"></i> На проверке/На доработке</span>';
    }else{
        return '<span><i class="fa fa-check-circle" aria-hidden="true" style="color:blue;"></i> Верифицирован</span>';
    }

}

function getArrayForTable($model, $type){
    /* @var $model Verifications*/
    $array = [];
    if ($type == 'main'){
        $item = [];
        $item['url'] = 'http://lseplatform.com'.$model->doc1;
        $item['name'] = 'Передняя сторона документа';
        $item['attr'] = 'doc1';
        $item['attr_status'] = $model->doc1_status;
        $item['is_set'] = isset($model->doc1);
        $array[] = $item;
        $item = [];
        $item['url'] = 'http://lseplatform.com'.$model->doc2;
        $item['name'] = 'Задняя сторона документа';
        $item['attr'] = 'doc2';
        $item['attr_status'] = $model->doc2_status;
        $item['is_set'] = isset($model->doc2);
        $array[] = $item;
        $item = [];
        $item['url'] = 'http://lseplatform.com'.$model->doc3;
        $item['attr'] = 'doc3';
        $item['name'] = 'Фото с паспортом на руках';
        $item['attr_status'] = $model->doc3_status;
        $item['is_set'] = isset($model->doc3);
        $array[] = $item;
    }else{
        $item = [];
        $item['url'] = 'http://lseplatform.com'.$model->doc4;
        $item['attr'] = 'doc4';
        $currUser = \common\models\User::findOne($model->user_id);
        $currCountry = \common\models\Countries::findOne($currUser->country_id);
        if (isset($currCountry)){
            $countryName = $currCountry->title;
        }else{
            $countryName = '';
        }
        $item['name'] = $countryName.' '.$model->city.', '.$model->address;
        $item['attr_status'] = 'doc4_status';
        $item['is_set'] = isset($model->doc4);
        $array[] = $item;
    }
    return $array;
}

$arrayVerify = getVerifyStatusesAsArray($model->stage);

$this->registerCssFile('/css/lightbox.css');
$this->registerJsFile('/js/lightbox.js',['depends'=>'yii\web\JqueryAsset']);
$script = <<<JS
    lightbox.option({
          'resizeDuration': 200,
          'wrapAround': true,
          'fitImagesInViewport':true
    });
    function getText(type, array){
        let text = '';
        if (type == 'main'){
            if (array[0] && array[1] && array[2]){
                text = "Поздравляем вы успешно прошли 2 категорию верификации. \\nТеперь вам доступно прохождение 3-ей категории верификации «Подтверждения адреса».";
            }else if(!array[0] && array[1] && array[2]){
                text = "К сожалению, мы не смогли верифицировать вашу личность.\\nПричина отклонения: Снимок передней стороны вашего документа не соответствует качеству требуемых в условиях верификации, а также основные данные документа отображаются не четко.\\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }else if(array[0] && !array[1] && array[2]){
                text = "К сожалению, мы не смогли верифицировать вашу личность. \\nПричина отклонения: Снимок задней стороны вашего документа не соответствует качеству требуемых в условиях верификации, а также основные данные документа отображаются не четко.\\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }else if(array[0] && array[1] && !array[2]){
                text = "К сожалению, мы не смогли верифицировать вашу личность. \\nПричина отклонения: Ваш снимок с документом не соответствует качеству требуемых в условиях верификации, а также основные данные документа отображаются не четко.\\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }else if(!array[0] && !array[1] && array[2]){
                text = "К сожалению, мы не смогли верифицировать вашу личность. \\nПричина отклонения: Снимок передней и задней стороны вашего документа не соответствует качеству требуемых в условиях верификации, а также основные данные документа отображаются не четко.\\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }else if(!array[0] && array[1] && !array[2]){
                text = "К сожалению, мы не смогли верифицировать вашу личность. \\nПричина отклонения: Снимок передней стороны вашего документа и ваш снимок с документом не соответствует качеству требуемых в условиях верификации, а также основные данные документа отображаются не четко.\\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }else if(array[0] && !array[1] && !array[2]){
                text = "К сожалению, мы не смогли верифицировать вашу личность. \\nПричина отклонения: Снимок задней стороны вашего документа и ваш снимок с документом не соответствует качеству требуемых в условиях верификации, а также основные данные документа отображаются не четко.\\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }else if(!array[0] && !array[1] && !array[2]){
                text = "К сожалению, мы не смогли верифицировать вашу личность. \\nПричина отклонения: Снимок передней, задней сторон вашего документа и ваш снимок с документом не соответствует качеству требуемых в условиях верификации, а также основные данные документа отображаются не четко.\\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }
        }else{
            if (array[0]){
                text = "Вы успешно прошли верификацию.";
            }else{
                text = "К сожалению, мы не смогли верифицировать ваши адресные данные. \\nПричина отклонения: ____________. \\nПожалуйста, устраните вышеуказанные замечания и пройдите верификацию снова!";
            }
            
        }
        return text;
    }
    function createComment(type){
        if (type == 'main'){
            let statuses = [];
            $('#main-doc .list').each(function (id, obj){
                if ($(obj).attr('data-status') == '1'){
                    statuses[id] = true;
                }else{
                    statuses[id] = false;
                }
            });
            let text = getText(type, statuses);
            $("#verifications-comment").val(text);
            if (statuses[0] === false){
                $("#verifications-doc1_status").prop('checked',false);
            }else{
                $("#verifications-doc1_status").prop('checked',true);
            }
            if (statuses[1] === false){
                $("#verifications-doc2_status").prop('checked',false);
            }else{
                $("#verifications-doc2_status").prop('checked',true);
            }
            if (statuses[2] === false){
                $("#verifications-doc3_status").prop('checked',false);
            }else{
                $("#verifications-doc3_status").prop('checked',true);
            }
        }else{
            let statuses = [];
            $('#address-doc .list').each(function (id, obj){
                if ($(obj).attr('data-status') == '1'){
                    statuses[id] = true;
                }else{
                    statuses[id] = false;
                }
            });
            let text = getText(type, statuses);
            $("#verifications-comment").val(text);
            if (statuses[0]){
                $("#verifications-doc4_status").prop('checked',true);
            }else{
                $("#verifications-doc4_status").prop('checked',false);
            }
            
            
        }
    }
    $('.accept').click(function (e){
        e.preventDefault();
        $(this).closest('.list').removeClass('disapproved');
        $(this).closest('.list').addClass('active');
        $(this).closest('.list').attr('data-status', '1');
        $(this).closest('.list').find('.list-status').text('Одобрено');
        let type = $(this).closest('.list').attr('data-type');
        createComment(type);
    });
    $('.cancel').click(function (e){
        e.preventDefault();
        $(this).closest('.list').removeClass('active');
        $(this).closest('.list').addClass('disapproved');
        $(this).closest('.list').attr('data-status', '0');
        $(this).closest('.list').find('.list-status').text('Отказ');
        let type = $(this).closest('.list').attr('data-type');
        createComment(type);
    });
JS;

$this->registerJs($script);
?>
<style>
    .verifications-view .col-md-3 h4{
        text-align: left;
    }
    ul{
        list-style: none;
    }
    .box{
        border-radius: 8px;
        background-color: rgba(246, 250, 255, .5);
        padding: 20px;
    }
    .list{
        display: flex;
        align-items: center;
        border-radius: 8px;
    }
    .list li{
        width: 20%;
        font-size: 1.3rem;
    }
    .list p{
        margin: 0;
    }
    .list a {
        font-size: 1.3rem;
    }

    .list-status{
        display: none;
    }
    .list-link{
        width: 40%!important;
    }
    .active{
        color: rgb(0, 40, 0);
        background: rgba(165, 255, 165, .3);
    }
    .active .list-status{
        display: block;
    }
    .active .list-status .yes{
        display: block;
    }
    .disapproved{
        color: rgb(31, 1, 1);
        background: rgba(252, 148, 148, .3);
    }
    .disapproved .list-status{
        display: block;
    }
    .disapproved .list-status .no{
        display: block;
    }
    .text{
        display: none;
    }
    #textarea{
        display: none;
    }
    .btn-group{
        display: none;
    }

</style>
<div class="verifications-view container">
        <div class="row">
            <div class="col-md-3"><h4>Пользователь:</h4></div>
            <div class="col-md-3"><h4><?=$user['fio']?></h4></div>
        </div>
        <div class="row">
            <div class="col-md-3"><h4>Логин:</h4></div>
            <div class="col-md-3"><h4><a href="/users/view?id=<?=$user['id']?>"><?=$user['username']?></a></h4></div>
        </div>
        <div class="row">
            <div class="col-md-3"><h4>Дата запроса:</h4></div>
            <div class="col-md-3"><h4><?=date('d.m.Y H:i',$model->time)?></h4></div>
        </div>
        <div class="row">
            <div class="col-md-3"><h4>Стадия:</h4></div>
            <div class="col-md-3"><h4><?=$model->getStageName();?></h4></div>
        </div>
        <div class="row">
            <div class="col-md-3"><h4>Верификация почты:</h4></div>
            <div class="col-md-3"><h4><?=getArrayIcon($arrayVerify['email']);?></h4></div>
        </div>
        <div class="row">
            <div class="col-md-3"><h4>Верификация документов:</h4></div>
            <div class="col-md-3"><h4><?=getArrayIcon($arrayVerify['main']);?></h4></div>
        </div>
        <div class="row">
            <div class="col-md-3"><h4>Верификация адреса:</h4></div>
            <div class="col-md-3"><h4><?=getArrayIcon($arrayVerify['address']);?></h4></div>
        </div>
        <?if ($arrayVerify['main'] == 0):?>
            <div id="main-doc">
                <?foreach (getArrayForTable($model, 'main') as $item):?>
                    <ul class="list disapproved" data-status = '0' data-type="main">
                        <li class="list-name"><p><?=$item['name'];?></p></li>
                        <li class="list-link">
                            <?if($item['is_set']):?>
                                <?=Html::a('Просмотр', $item['url'],['data-lightbox'=>'image-2','data-title'=>'My caption','class'=>'file']);?>
                            <?endif;?>
                        </li>
                        <li class="list-buttons">
                            <button class="accept btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i></button>
                            <button class="cancel btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i></button>
                        </li>
                        <li class="list-status">Отказ</li>
                    </ul>
                <?endForeach;?>
            </div>
            <div class="verifications-form">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'comment')->textarea(['rows' => '6']) ?>
                <div style="display: none">
                    <?= $form->field($model, 'doc1_status')->checkbox() ?>
                    <?= $form->field($model, 'doc2_status')->checkbox() ?>
                    <?= $form->field($model, 'doc3_status')->checkbox() ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        <?elseif ($arrayVerify['main'] == 1):?>
            <div class="row">
                <div class="col-md-3"><h4>Паспорт/Удостоверение:</h4></div>
                <div class="col-md-3">
                    <?if(!empty($model['doc1'])){?>
                        <?$doc1Url = 'http://lseplatform.com'.$model->doc1; ?>
                        <h4><?=Html::a('Просмотр', $doc1Url,['data-lightbox'=>'image-1','data-title'=>'My caption','class'=>'file']);?></h4>
                    <?}?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"><h4>Паспорт/Удостоверение (обратная сторона):</h4></div>
                <div class="col-md-3">
                    <?if(!empty($model['doc2'])){?>
                        <?$doc2Url = 'http://lseplatform.com'.$model->doc2; ?>
                        <h4><?=Html::a('Просмотр', $doc2Url,['data-lightbox'=>'image-2','data-title'=>'My caption','class'=>'file']);?></h4>
                    <?}?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"><h4>Фото с паспортом на руках:</h4></div>
                <div class="col-md-3">
                    <?if(!empty($model['doc3'])){?>
                        <?$doc3Url = 'http://lseplatform.com'.$model->doc3; ?>
                        <h4><?=Html::a('Просмотр', $doc3Url,['data-lightbox'=>'image-3','data-title'=>'My caption','class'=>'file']);?></h4>
                    <?}?>
                </div>
            </div>
        <?endif;?>
        <?if($arrayVerify['address'] == 0):?>
            <div id="address-doc">
                <?foreach (getArrayForTable($model, 'address') as $item):?>
                    <ul class="list disapproved" data-status = '0' data-type="address">
                        <li class="list-name"><p><?=$item['name'];?></p></li>
                        <li class="list-link">
                            <?if($item['is_set']):?>
                                <?=Html::a('Просмотр', $item['url'],['data-lightbox'=>'image-2','data-title'=>'My caption','class'=>'file']);?>
                            <?endif;?>
                        </li>
                        <li class="list-buttons">
                            <button class="accept btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i></button>
                            <button class="cancel btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i></button>
                        </li>
                        <li class="list-status">Отказ</li>
                    </ul>
                <?endForeach;?>
                <div class="verifications-form">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'comment')->textarea(['rows' => '6']) ?>
                    <div style="display: block">
                        <?= $form->field($model, 'doc4_status')->checkbox() ?>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?elseif ($arrayVerify['address'] == 1):?>
            <div class="row">
                <div class="col-md-3"><h4>Документ подтверждающий адрес:</h4></div>
                <div class="col-md-3">
                    <?if(!empty($model['doc1'])){?>
                        <?$doc1Url = 'http://lseplatform.com'.$model->doc1; ?>
                        <h4><?=Html::a('Просмотр', $doc1Url,['data-lightbox'=>'image-1','data-title'=>'My caption','class'=>'file']);?></h4>
                    <?}?>
                </div>
            </div>
        <?endif;?>






</div>
<!--<div class="verifications-view">-->
<!---->
<!--    <p>-->
<!--        --><?//= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--    </p>-->
<!---->
<!--    <h4>Пользователь: --><?//=$user['fio']?><!--</h4>-->
<!--    <h4>Логин: <a href="/users/view?id=--><?//=$user['id']?><!--">--><?//=$user['username']?><!--</a></h4>-->
<!--    <h4>Дата запроса: --><?//=date('d.m.Y H:i',$model->time)?><!--</h4>-->
<!--    <h4>Стадия: --><?//=$model->getStageName();?><!--</h4>-->
<!--    <h4>Документы: </h4>-->
<!--    <h4>Паспорт/Удостоверение:-->
<!--        --><?//if(!empty($model['doc1'])){?>
<!--            <a href="--><?//=Yii::$app->params['mainUrl']?><!----><?//=$model->doc1?><!--" class="btn btn-link">Скачать</a>-->
<!--        --><?//}?>
<!--    </h4>-->
<!--    <h4>Паспорт/Удостоверение (обратная сторона):-->
<!--        --><?//if(!empty($model['doc2'])){?>
<!--            <a href="--><?//=Yii::$app->params['mainUrl']?><!----><?//=$model->doc2?><!--" class="btn btn-link">Скачать</a>-->
<!--        --><?//}?>
<!--    </h4>-->
<!--    <h4>Фото с паспортом на руках:-->
<!--        --><?//if(!empty($model['doc3'])){?>
<!--            <a href="--><?//=Yii::$app->params['mainUrl']?><!----><?//=$model->doc3?><!--" class="btn btn-link">Скачать</a>-->
<!--        --><?//}?>
<!--    </h4>-->
<!--    <h4>Адрес проживания:-->
<!--        --><?//if(!empty($model['address'])){?>
<!--            <p>--><?//=$model['city'].','.$model['address']?><!--</p>-->
<!--        --><?//}?>
<!--        --><?//if(!empty($model['doc4'])){?>
<!--            <a href="--><?//=Yii::$app->params['mainUrl']?><!----><?//=$model->doc4?><!--" class="btn btn-link">Скачать</a>-->
<!--        --><?//}?>
<!--    </h4>-->
<!---->
<!---->
<!--</div>-->
