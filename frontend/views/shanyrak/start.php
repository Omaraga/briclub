<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = "Shanyrak";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
if($program['id'] == 5){
    $this->registerJs('
              
              $( ".field-shanyrakbedform-sum_kv_all_2" ).hide();
              $( ".field-shanyrakbedform-sum_kv_all_3" ).hide();
             
              $( "#shanyrakbedform-sum_month_1" ).val(1417);
              $( "#shanyrakbedform-sum_month_2" ).val(306);
              $( "#shanyrakbedform-rooms" ).change(function() {
              id = $(this).val();
              if(id == 1){
                $( ".field-shanyrakbedform-sum_kv_all_1" ).show();
                $( ".field-shanyrakbedform-sum_kv_all_2" ).hide();
                $( ".field-shanyrakbedform-sum_kv_all_3" ).hide();
                $( "#shanyrakbedform-sum_first" ).val(2500);
                price = 22000;
                first = 2500;
                
                
              }
              if(id == 2){
                $( ".field-shanyrakbedform-sum_kv_all_2" ).show();
                $( ".field-shanyrakbedform-sum_kv_all_1" ).hide();
                $( ".field-shanyrakbedform-sum_kv_all_3" ).hide();
                $( "#shanyrakbedform-sum_first" ).val(3500);
                price = 29000;
                first = 3500;
                
              }
              if(id == 3){
                $( ".field-shanyrakbedform-sum_kv_all_3" ).show();
                $( ".field-shanyrakbedform-sum_kv_all_1" ).hide();
                $( ".field-shanyrakbedform-sum_kv_all_2" ).hide();
                $( "#shanyrakbedform-sum_first" ).val(5000);
                price = 36000;
                first = 5000;
                
              }
              term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
        });
        
        $( "#shanyrakbedform-term1" ).change(function() {
              term = $(this).val();
              id = $( "#shanyrakbedform-rooms" ).val();
              if(id == 1){
                first = 2500;     
                price = $( "#shanyrakbedform-sum_kv_all_1" ).val();    
              }
              if(id == 2){
                first = 3500;
                price = $( "#shanyrakbedform-sum_kv_all_2" ).val();
              }
              if(id == 3){
                first = 5000;
                price = $( "#shanyrakbedform-sum_kv_all_3" ).val();
              }
              res = (price/2 - first)/term;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
            
        });
        
        $( "#shanyrakbedform-term2" ).change(function() {
              term = $(this).val();
              id = $( "#shanyrakbedform-rooms" ).val();
              if(id == 1){ 
                price = $( "#shanyrakbedform-sum_kv_all_1" ).val();    
              }
              if(id == 2){
                price = $( "#shanyrakbedform-sum_kv_all_2" ).val();
              }
              if(id == 3){
                price = $( "#shanyrakbedform-sum_kv_all_3" ).val();
              }
              res = (price/2)/term;
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res));
            
        });
        
        $( "#shanyrakbedform-sum_kv_all_3" ).change(function() {
              term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              id = $( "#shanyrakbedform-rooms" ).val();
              price = $(this).val();
              if(id == 1){
                first = 2500;     
              }
              if(id == 2){
                first = 3500;
              }
              if(id == 3){
                first = 5000;
              }
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
            
        });
        $( "#shanyrakbedform-sum_kv_all_2" ).change(function() {
             term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              id = $( "#shanyrakbedform-rooms" ).val();
              price = $(this).val();
              if(id == 1){
                first = 2500;     
              }
              if(id == 2){
                first = 3500;
              }
              if(id == 3){
                first = 5000;
              }
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
            
        });
        $( "#shanyrakbedform-sum_kv_all_1" ).change(function() {
              term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              id = $( "#shanyrakbedform-rooms" ).val();
              price = $(this).val();
              if(id == 1){
                first = 2500;     
              }
              if(id == 2){
                first = 3500;
              }
              if(id == 3){
                first = 5000;
              }
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
            
        });
    ');
}elseif ($program['id'] == 6){
    $this->registerJs('
              
              $( ".field-shanyrakbedform-sum_auto_all_2" ).hide();
              $( ".field-shanyrakbedform-sum_auto_all_3" ).hide();
              $( ".field-shanyrakbedform-sum_auto_all_4" ).hide();
             
              $( "#shanyrakbedform-sum_month_1" ).val(417);
              $( "#shanyrakbedform-sum_month_2" ).val(417);
              $( "#shanyrakbedform-rooms" ).change(function() {
              id = $(this).val();
              if(id == 1){
                $( ".field-shanyrakbedform-sum_auto_all_1" ).show();
                $( ".field-shanyrakbedform-sum_auto_all_2" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_3" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_4" ).hide();
                $( "#shanyrakbedform-sum_first" ).val(2500);
                price = 10000;
                first = 2500;
                
                
              }
              if(id == 2){
                $( ".field-shanyrakbedform-sum_auto_all_2" ).show();
                $( ".field-shanyrakbedform-sum_auto_all_1" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_3" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_4" ).hide();
                $( "#shanyrakbedform-sum_first" ).val(3500);
                price = 15000;
                first = 3500;
                
              }
              if(id == 3){
                $( ".field-shanyrakbedform-sum_auto_all_3" ).show();
                $( ".field-shanyrakbedform-sum_auto_all_1" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_2" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_4" ).hide();
                $( "#shanyrakbedform-sum_first" ).val(4500);
                price = 20000;
                first = 4500;
                
              }
              if(id == 4){
                $( ".field-shanyrakbedform-sum_auto_all_4" ).show();
                $( ".field-shanyrakbedform-sum_auto_all_1" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_2" ).hide();
                $( ".field-shanyrakbedform-sum_auto_all_3" ).hide();
                $( "#shanyrakbedform-sum_first" ).val(7500);
                price = 36000;
                first = 7500;
                
              }
              term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
        });
        
        $( "#shanyrakbedform-term1" ).change(function() {
              term = $(this).val();
              id = $( "#shanyrakbedform-rooms" ).val();
              if(id == 1){
                first = 2500;     
                price = $( "#shanyrakbedform-sum_auto_all_1" ).val();    
              }
              if(id == 2){
                first = 3500;
                price = $( "#shanyrakbedform-sum_auto_all_2" ).val();
              }
              if(id == 3){
                first = 4500;
                price = $( "#shanyrakbedform-sum_auto_all_3" ).val();
              }
              res = (price/2 - first)/term;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
            
        });
        
        $( "#shanyrakbedform-term2" ).change(function() {
              term = $(this).val();
              id = $( "#shanyrakbedform-rooms" ).val();
              if(id == 1){ 
                price = $( "#shanyrakbedform-sum_auto_all_1" ).val();    
              }
              if(id == 2){
                price = $( "#shanyrakbedform-sum_auto_all_2" ).val();
              }
              if(id == 3){
                price = $( "#shanyrakbedform-sum_auto_all_3" ).val();
              }
              res = (price/2)/term;
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res));
            
        });
        
        $( "#shanyrakbedform-sum_auto_all_3" ).change(function() {
              term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              id = $( "#shanyrakbedform-rooms" ).val();
              price = $(this).val();
              if(id == 1){
                first = 2500;     
              }
              if(id == 2){
                first = 3500;
              }
              if(id == 3){
                first = 4500;
              }
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
            
        });
        $( "#shanyrakbedform-sum_auto_all_2" ).change(function() {
             term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              id = $( "#shanyrakbedform-rooms" ).val();
              price = $(this).val();
              if(id == 1){
                first = 2500;     
              }
              if(id == 2){
                first = 3500;
              }
              if(id == 3){
                first = 4500;
              }
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
            
        });
        $( "#shanyrakbedform-sum_auto_all_1" ).change(function() {
              term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              id = $( "#shanyrakbedform-rooms" ).val();
              price = $(this).val();
              if(id == 1){
                first = 2500;     
              }
              if(id == 2){
                first = 3500;
              }
              if(id == 3){
                first = 4500;
              }
              res = (price/2 - first)/term;
              res2 = (price/2)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
            
        });
    ');
}elseif ($program['id'] == 7){
    $this->registerJs('
              
             
              $( "#shanyrakbedform-sum_month_1" ).val(204);
              $( "#shanyrakbedform-sum_month_2" ).val(89);
             
        
        $( "#shanyrakbedform-term1" ).change(function() {
              term = $(this).val();
              first = 500;     
              price = $( "#shanyrakbedform-sum_tech_all_1" ).val();  
              
              res = ((price*5)/10 - first)/term;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
            
        });
        
        $( "#shanyrakbedform-term2" ).change(function() {
              term = $(this).val();
              price = $( "#shanyrakbedform-sum_tech_all_1" ).val();    
              
              res = ((price*5)/10)/term;
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res));
            
        });
        
        $( "#shanyrakbedform-sum_tech_all_1" ).change(function() {
              term = $("#shanyrakbedform-term1").val();
              term2 = $("#shanyrakbedform-term2").val();
              
              first = 500;
              price = $(this).val();
              
              res = ((price*5)/10 - first)/term;
            
              res2 = ((price*5)/10)/term2;
              $( "#shanyrakbedform-sum_month_1" ).val(Math.ceil(res));
              $( "#shanyrakbedform-sum_month_2" ).val(Math.ceil(res2));
            
        });
        
    ');
}

     ?>
    <?
    $flashes = Yii::$app->session->allFlashes;
    if(!empty($flashes)){
        foreach ($flashes as $key => $flash) {?>
            <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
                <?=$flash?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?}}
    ?>
    <main class="transaction">
        <div class="container">
            <div class="hgroup">
                <h1 class="h1">Участвовать в Shanyrak </h1>
                <h5 class="h5">Программа <?=$program['title']?></h5>
                <h5 class="h5">Стоимость: $<?=$program['price']?></h5>
                <div class="hline"></div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-3" >
                    <?if($step == 1){?>
                        <h3>Шаг 1. Активируйте Cell Account</h3>
                        <div class="col-lg-10 mt-4">
                            <h2 class="h2 mb-0">Оплатить с баланса</h2>
                            <p>Ваш баланс: <?=$user['w_balans']?></p>
                            <form class="form" action="/shanyrak/activ" method="GET">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group mt-3">
                                            <input type="hidden" placeholder="Введите сумму" class="form-control" name="program" value="<?=$program['id']?>">
                                            <input type="text" placeholder="Введите сумму" class="form-control" name="sum" value="<?=$price?>" required disabled><BR>
                                        </div>
                                        <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" name="PAYMENT_METHOD" value="Оплатить">


                                    </div>
                                </div>
                            </form>

                        </div>
                    <?}elseif($step == 2){?>
                        <h3>Шаг 2. Заполните заявку</h3>
                        <?php
                            $model = new \frontend\models\forms\ShanyrakBedForm();
                        ?>
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-lg-6 mt-3" >

                                <?= $form->field($model, 'parent')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'name')->textInput(['maxlength' => true,'value'=>$user['firstname']]) ?>

                                <?= $form->field($model, 'iin')->textInput() ?>

                                <?= $form->field($model, 'doc_num')->textInput() ?>

                                <?= $form->field($model, 'address')->textarea(['rows' => 2]) ?>

                                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'email')->textInput(['maxlength' => true,'value'=>$user['email']]) ?>

                                <div class="form-group">
                                    <? echo $form->field($model, 'file')->fileInput(['class'=>'form-control-file btn btn-link pl-0']);  ?>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-3" >
                                <?
                                if($program['id'] == 5){?>

                                 <?= $form->field($model, 'rooms')->dropDownList([1=>'Однокомнатная до $22 000',2=>'Двухкомнатная до $29 000',3=>'Трехкомнатная до $36 000']); ?>

                                <?= $form->field($model, 'sum_kv_all_1')->textInput(['value'=>22000]) ?>
                                <?= $form->field($model, 'sum_kv_all_2')->textInput(['value'=>29000]) ?>
                                <?= $form->field($model, 'sum_kv_all_3')->textInput(['value'=>36000]) ?>

                                <?= $form->field($model, 'sum_first')->textInput(['value'=>2500,'disabled' => true]) ?>

                                <?= $form->field($model, 'term1')->dropDownList([6=>'6 месяцев',12=>'12 месяцев',24=>'24 месяцев',48=>'48 месяцев']); ?>

                                <?= $form->field($model, 'sum_month_1')->textInput(['disabled' => true]) ?>

                                    <?= $form->field($model, 'term2')->dropDownList([36=>'36 месяцев',48=>'48 месяцев',60=>'60 месяцев',120=>'120 месяцев']); ?>
                                    <?= $form->field($model, 'sum_month_2')->textInput(['disabled' => true]) ?>
                                <?}elseif ($program['id'] == 6){?>
                                    <?= $form->field($model, 'rooms')->dropDownList([1=>'Авто до $10 000',2=>'Авто до $15 000',3=>'Авто до $20 000',4=>'Авто до $36 000']); ?>

                                    <?= $form->field($model, 'sum_auto_all_1')->textInput(['value'=>10000]) ?>
                                    <?= $form->field($model, 'sum_auto_all_2')->textInput(['value'=>15000]) ?>
                                    <?= $form->field($model, 'sum_auto_all_3')->textInput(['value'=>20000]) ?>
                                    <?= $form->field($model, 'sum_auto_all_4')->textInput(['value'=>36000]) ?>

                                    <?= $form->field($model, 'sum_first')->textInput(['value'=>2500,'disabled' => true]) ?>

                                    <?= $form->field($model, 'term1')->dropDownList([6=>'6 месяцев',9=>'9 месяцев',12=>'12 месяцев',24=>'24 месяцев']); ?>

                                    <?= $form->field($model, 'sum_month_1')->textInput(['disabled' => true]) ?>

                                    <?= $form->field($model, 'term2')->dropDownList([12=>'12 месяцев',24=>'24 месяцев']); ?>
                                    <?= $form->field($model, 'sum_month_2')->textInput(['disabled' => true]) ?>
                                <?}elseif ($program['id'] == 7){?>

                                    <?= $form->field($model, 'sum_tech_all_1')->textInput(['value'=>2000]) ?>

                                    <?= $form->field($model, 'sum_first')->textInput(['value'=>500,'disabled' => true]) ?>

                                    <?= $form->field($model, 'term1')->dropDownList([3=>'3 месяцев', 6=>'6 месяцев',12=>'12 месяцев']); ?>

                                    <?= $form->field($model, 'sum_month_1')->textInput(['disabled' => true]) ?>

                                    <?= $form->field($model, 'term2')->dropDownList([12=>'12 месяцев', 24=>'24 месяцев']); ?>
                                    <?= $form->field($model, 'sum_month_2')->textInput(['disabled' => true]) ?>
                                <?}?>

                            </div>
                        </div>




                        <?= $form->field($model, 'program_id')->hiddenInput(['value'=>$program['id']])->label(false) ?>

                        <?= $form->field($model, 'user_id')->hiddenInput(['value'=>$user['id']])->label(false) ?>

                        <?= $form->field($model, 'time')->hiddenInput(['value'=>time()])->label(false) ?>
                        <div class="form-group">
                            <?= Html::submitButton('Оставить заявку', ['class' => 'btn btn-success']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    <?}elseif($step == 3){?>
                        <?
                            $user_bed = \common\models\ShanyrakBeds::find()->where(['user_id'=>$user['id'],'program_id'=>$program['id']])->one();
                        ?>
                        <h3>Шаг 3. Подпишите договор</h3>
                        <?if($user_bed['status']==2){?>
                            <p>Ваша заявка находится на рассмотрении</p>
                        <?}?>
                        <?if($user_bed['status']==1){?>
                            <p>Пожалуйста, скачайте договор, подпишите и загрузите отсканированный документ.</p>
                            <p><a href="<?=$user_bed['doc2']?>" class="btn btn-success">Скачать</a></p>
                            <?php
                            $doc_model = new \frontend\models\forms\Doc3Form();
                            $form = ActiveForm::begin(['action'=>'/shanyrak/doc3','options' => ['enctype' => 'multipart/form-data']]); ?>

                            <?= $form->field($doc_model, 'file')->fileInput(['class'=>'form-control-file btn btn-link pl-0']);  ?>
                            <?= $form->field($doc_model, 'bed_id')->hiddenInput(['value'=>$user_bed['id']])->label(false);  ?>

                            <div class="form-group">
                                <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        <?}?>
                    <?}elseif($step == 4){?>
                        <?
                        $user_bed = \common\models\ShanyrakBeds::find()->where(['user_id'=>$user['id'],'program_id'=>$program['id']])->one();
                        ?>
                        <h3>Шаг 4. Внесите первый платеж</h3>
                        <?if($user_bed['status']==2){?>
                            <p>Вы можете внести первый взнос после одобрения подписанного договора</p>
                        <?}?>
                        <?if($user_bed['status']==1){
                            $shanyrak_user_pay = \common\models\ShanyrakUserPays::find()->where(['user_id'=>$user['id'],'program_id'=>$program['id'],'type'=>1])->one();
                            $need_sum = $shanyrak_user_pay['sum_need'];
                            ?>
                            <div class="col-lg-10 mt-4">
                                    <p>Ваш баланс: <?=$user['w_balans']?></p>
                                    <a href="/profile/perfect" class="btn btn-link">Пополнить баланс</a>
                                    <form class="form" action="/shanyrak/pay" method="GET">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group mt-3">
                                                    <input type="hidden" placeholder="Введите сумму" class="form-control" name="pay_id" value="<?=$shanyrak_user_pay['id']?>">
                                                    <input type="text" placeholder="Введите сумму" class="form-control" name="sum" value="<?=$need_sum?>" required disabled><BR>
                                                </div>
                                                <input class="btn btn-primary mt-3 pl-5 pr-5 pt-3 pb-3" type="submit" value="Оплатить">


                                            </div>
                                        </div>
                                    </form>

                            </div>
                        <?}?>
                    <?}?>

                </div>
            </div>

        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>