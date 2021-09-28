<?php

/* @var $this yii\web\View */

use common\models\Lessons;
use common\models\UserLessons;
use yii\httpclient\Client;


$this->title = "Токены";
if(!Yii::$app->user->isGuest){
    $user = \common\models\User::findOne(Yii::$app->user->identity['id']);
}

$tokens = \common\models\Tokens::findOne(['user_id'=>$user['id']]);
$node = \common\models\TokenNodes::findOne(['user_id' => $user->id]);
$nodeQuery = \common\models\TokenNodesQueries::findOne(['user_id' => $user->id]);
$profit = 0;

$actions = \common\models\Actions::find()->orderBy(['id' => SORT_DESC])->limit(10)->all();

if($node != null){
    $node_actions = \common\models\Actions::find()->where(['node_id' => $node->id])
        ->orderBy(['id' => SORT_DESC])->limit(10)->all();
}
else{
    $node_actions = \common\models\Actions::find()
        ->orderBy(['id' => SORT_DESC])->limit(0)->all();
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
    <main class="home">
        <div class="container">
            <div class="hgroup pt-3">
                <h3 class="h3">Блокчейн экосистема</h3>
                <h5 class="h5"> Компания “Best Road Investment” разрабатывает собственную блокчейн экосистему, которая дает использовать пользователям мультифункциональной платформы безграничные возможности цифрового мира и вводит токенизацию.
                    Токены GRC можно использовать внутри проектов компании и получать дивиденды, покупать и продавать товары в  интернет магазинах и торговой площадке, совершать продажи и покупку токенов на внутреннем обменнике. В дальнейшем токены GRC могут быть обменены на акции компании, после проведения IPO.</h5>
                <div class="hline"></div>
            </div>
            <div class="card-group">
                <div class="row">
                    <div class="col-lg-4">

                        <div class="card shakyrak">
                            <div class="card-header">
                                <div class="title-card">
                                    <div class="icon"><div class="shakyrak-icon"></div></div>
                                    <span class="lead">Токены</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="main-text">
                                    <span class="small">Текущий баланс: <? if(!empty($tokens)){echo $tokens['balans'];}else{echo "0.00";}?></span>
                                    <span class="small">GRC</span>

                                    <br>

                                    <?if(!empty($nodeQuery)){?>
                                        <span class="small">Занесено в ноду: <? if(!empty($nodeQuery)){echo $nodeQuery['tokens_count'];}else{echo "0.00";}?></span>
                                        <span class="small">GRC</span>

                                        <br>

                                        <span class="small">Итого: <? if(empty($tokens)){$tokens['balans'] = 0;} if(empty($nodeQuery)){$nodeQuery['tokens_count'] = 0;} echo $tokens['balans'] + $nodeQuery['tokens_count'];?></span>
                                        <span class="small">GRC</span>
                                    <?}?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="/tokens/get" class="btn btn-link">Пополнить</a>

                                <a href="/tokens/token-transfer" class="btn btn-link">Перевести</a>


                                <a href="/tokens" class="btn btn-link">Подробнее</a>

                            </div>
                        </div>

                    </div>
                        <div class="col-lg-4">
                        <div class="card matrix">
                            <div class="card-header">
                                <div class="title-card">
                                    <div class="icon"><div class="matrix-icon"></div></div>
                                    <span class="lead">Моя нода</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-col" style="width: 290px;">
                                    <div class="main-text">
                                        <?if($node != null){?>
                                            <span class="regular-text">Всего закрытых блоков
                                                <span style="float: right;">
                                                    0
                                                </span>
                                            </span>
                                            <br>
                                        <span class="regular-text">Количество транзакций
                                                <span style="float: right;">
                                                    0
                                                </span>
                                            </span>
                                        <br>
                                        <?}else if($nodeQuery != null){$profit = 0?>
                                            <span class="regular-text">Ваша заявка была принята в обработку</span>
                                        <?}else{$profit = 0?>
                                            <a href="tokens/node-query" class="btn btn-link">Стать нодой</a>
                                        <?}?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">

                                    <a href="#" class="btn btn-link">Заработок: </a>
                                    <a href="#" class="btn btn-link"><?=$profit?> GRN</a>

                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="card bonus-program ">
                            <div class="card-header">
                                <div class="title-card">
                                    <div class="icon"><div class="bonus-program-icon"></div></div>
                                    <span class="lead">Все ноды</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-col" style="width: 290px;">
                                    <div class="main-text">
                                        <?if($node != null){?>
                                            <span class="regular-text">Всего закрытых блоков
                                                <span style="float: right;">
                                                    0
                                                </span>
                                            </span>
                                            <br>
                                            <span class="regular-text">Количество транзакций
                                                <span style="float: right;">
                                                    0
                                                </span>
                                            </span>
                                            <br>
                                        <?}else if($nodeQuery != null){$profit = 0?>
                                            <span class="regular-text">Ваша заявка была принята в обработку</span>
                                        <?}else{$profit = 0?>
                                            <a href="tokens/node-query" class="btn btn-link">Стать нодой</a>
                                        <?}?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="#" class="btn btn-link">Заработок: </a>
                                <a href="#" class="btn btn-link"><?=$profit?> GRN</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <style>
                table {
                    text-align: left;
                    border-collapse: separate;
                    border-spacing: 5px;
                    background: #ECE9E0;
                    color: #656665;
                    border: 16px solid #ECE9E0;
                    border-radius: 10px;
                }
                th {
                    font-size: 18px;
                    padding: 10px;
                }
                td {
                    background: #F5D7BF;
                    padding: 10px;
                    font-weight: bold;
                    border-radius: 3px;
                }
            </style>

            <ul class="nav nav-tabs mt-5 mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all-transactions" aria-selected="true">Все транзакции</a>
                </li>
                <? if($node != null){?>
                    <li class="nav-item">
                        <a class="nav-link" id="node-tab" data-toggle="tab" href="#node" role="tab" aria-controls="node-transactions" aria-selected="true">Транзакции ноды</a>
                    </li>
                <?}?>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-transactions">
                    <table>
                        <tr>
                            <th>Id транзакции</th>
                            <th>Сумма</th>
                            <th>Дата и время</th>
                            <th>Комиссия</th>
                            <th>Id блока</th>
                            <th>Id ноды</th>
                            <th>Логин</th>
                        </tr>
                        <?foreach ($actions as $action){ $login = \common\models\User::findOne(['id' => $action['user_id']])["username"]?>
                            <tr>
                                <td><?=$action['id']?></td>
                                <td><?=$action['sum']?></td>
                                <td><?=date("d.m.Y H:i", $action['time'])?></td>
                                <td><?=$action['fee']?></td>
                                <td><?=$action['block_id']?></td>
                                <td><?=$action['node_id']?></td>
                                <td><?=$login[0]?>*****<?=$login[strlen($login) - 1]?></td>
                            </tr>
                        <?}?>
                    </table>
                    <a href="/tokens/all-transactions" class="btn btn-success" style="margin: 10px 0 10px 0">Все транзакции</a>
                </div>

                <div class="tab-pane fade" id="node" role="tabpanel" aria-labelledby="node-transactions">
                    <table>
                        <tr>
                            <th>Id транзакции</th>
                            <th>Сумма</th>
                            <th>Дата и время</th>
                            <th>Комиссия</th>
                            <th>Id блока</th>
                            <th>Id ноды</th>
                            <th>Логин</th>
                        </tr>
                        <?foreach ($node_actions as $action){ $login = \common\models\User::findOne(['id' => $action['user_id']])["username"]?>
                            <tr>
                                <td><?=$action['id']?></td>
                                <td><?=$action['sum']?></td>
                                <td><?=date("d.m.Y H:i", $action['time'])?></td>
                                <td><?=$action['fee']?></td>
                                <td><?=$action['block_id']?></td>
                                <td><?=$action['node_id']?></td>
                                <td><?=$login[0]?>*****<?=$login[strlen($login) - 1]?></td>
                            </tr>
                        <?}?>
                    </table>
                    <a href="/tokens/node-transactions" class="btn btn-success" style="margin: 10px 0 10px 0">Все транзакции</a>
                </div>

            </div>

        </div>
    </main>



<?
echo \frontend\components\LoginWidget::widget();
?>