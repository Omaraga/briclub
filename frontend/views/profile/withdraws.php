<?php

/* @var $this yii\web\View */
use yii\httpclient\Client;


$this->title = "Транзакции";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}
$withdraws = \common\models\Withdraws::find()->where(['user_id'=>$user['id']])->orderBy('id desc')->all();
 ?>
    <main class="cours">
        <div class="container">
            <div class="row">
                <?/*=\frontend\components\NavWidget::widget()*/?>
                <main role="main" class="col-12">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center hgroup">
                        <h1 class="h1">Транзакции</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Получатель</th>
                                    <th scope="col">Сумма</th>
                                    <th scope="col">Комиссия</th>
                                    <th scope="col">На вывод</th>
                                    <th scope="col">Платежная система</th>
                                    <th scope="col">Время</th>
                                    <th scope="col">Статус</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?
                                $i = 0;
                                foreach ($withdraws as $withdraw) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$i?></th>
                                        <td><?=$withdraw['account']?></td>
                                        <td><?=$withdraw['sum']?></td>
                                        <td><?=$withdraw['fee']?></td>
                                        <td><?=$withdraw['sum2']?></td>
                                        <td>
                                            <?
                                                if($withdraw['system_id'] == 1){
                                                    echo "Advcash";
                                                }else{
                                                    echo "Perfect Money";
                                                } ?>
                                        </td>
                                        <td><?=date("d.m.Y H:i", $withdraw['time'])?></td>
                                        <td>
                                            <?
                                                if($withdraw['status'] == 3){
                                                    $color = "primary";
                                                    $text = "В обработке";
                                                }elseif($withdraw['status'] == 2){
                                                    $color = "danger";
                                                    $text = "Отменено";
                                                }elseif($withdraw['status'] == 1){
                                                    $color = "success";
                                                    $text = "Выполнено";
                                                }
                                            ?>
                                            <span class="badge badge-<?=$color?>"><?=$text?></span>
                                        </td>
                                    </tr>
                                <? }?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </main>

            </div>
        </div>



    </main>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>