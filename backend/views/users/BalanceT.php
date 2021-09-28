<?php
use common\models\ActionTypes;
use yii\helpers\Html;
$this->title="Статистика баланса ";
?>
<div>
    <?
    $sum = \common\models\User::find()->sum('w_balans');
    $cv_pv=$total_cv+$total_pv;
    echo  "Всего на балансе пользователей: ".$sum. "<br>" ."Всего выведено: ".$total_withdraws. "PV<br>"."PV: " .$total_pv. "<br>" . "CV: " .$total_cv . "<br>"."CV+PV: ".$cv_pv;

    ?>
</div>
