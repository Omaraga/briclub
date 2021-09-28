<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = "Shanyrak Калькулятор";
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
                <h1 class="h1">Калькулятор Shanyrak </h1>
                <h5 class="h5">Программа </h5>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?if($program['id'] == 5){echo "active";}?>" id="news-tab" href="/shanyrak/calculator?program=5" role="tab" aria-controls="news" aria-selected="true">Недвижимость</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?if($program['id'] == 6){echo "active";}?>" id="profile-tab"  href="/shanyrak/calculator?program=6" role="tab" aria-controls="profile" aria-selected="false">Авто</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?if($program['id'] == 7){echo "active";}?>" id="profile-tab"  href="/shanyrak/calculator?program=7" role="tab" aria-controls="profile" aria-selected="false">Бытовая техника</a>
                    </li>
                </ul>
                <br>
                <h5 class="h5">Стоимость: $<?=$program['price']?></h5>
                <div class="hline"></div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-3" >

                        <?php
                            $model = new \frontend\models\forms\ShanyrakBedForm();
                        ?>
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
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

                        <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>
    </main>

<?
echo \frontend\components\LoginWidget::widget();
?>