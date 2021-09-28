<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$dataProvider = new ActiveDataProvider([
    'query' => $data,
    'pagination' => [
        'pageSize' => 20,
    ],
]);
$this->registerCssFile('@web/css/bootstrap.css');
?>
<div class="container">
	<div class="table-responsive">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
    ]);
    ?>
    </div>
</div>
