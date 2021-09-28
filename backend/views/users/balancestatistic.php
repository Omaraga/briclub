<?php
use common\models\ActionTypes;
use yii\helpers\Html;
$this->title="Статистика баланса пользователя: ".$user->username;
?>
<div>
    <?
    echo '<br>'."Пользователь: " .$user->username."(".$user->fio. ")<br>" . "Всего пополнено: " . $statisticArray['total_in']. "<br>" . "Доход: " . $statisticArray['total_transfered'] ."<br>". "Всего потрачено: " .$statisticArray['total_out'] ."<br>" . "Всего выведено: " .$statisticArray['total_cashed'] ."<br>" . "Баланс: " .$user->w_balans.  "<br>". "PV: " .$statisticArray['pv']. "<br>" . "CV: " . $statisticArray['cv']  . "<br>";
    echo $tableHtml;
    ?>
</div>
