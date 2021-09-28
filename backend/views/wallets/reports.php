<?php


use common\models\Actions;
use common\models\User;
use kartik\export\ExportMenu;
use kartik\typeahead\TypeaheadBasic;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчеты по токенам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actions-index">
    <div class="tab-content">
        <?
        echo "<br>Все токены: ".$tokens_all;
        echo "<br>Основные токены: ".$tokens_main;
        echo "<br>Бонусные токены: ".$tokens_bonus;
        echo "<br>";
        echo "<br>Купленные токены: ".$all_buyed_tokens;
        echo "<br>Пополненные админом токены: ".$all_admin_tokens;
        echo "<br>Всего: ".($all_buyed_tokens+$all_admin_tokens);
        echo "<br>Потраченные токены: ".$all_payed_tokens;
        echo "<br>Итого: ".($all_buyed_tokens+$all_admin_tokens-$all_payed_tokens);
        ?>
    </div>

</div>
