<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <?php $this->registerCsrfMetaTags() ?>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <title><?= Html::encode($this->title) ?></title>
    <script src="https://use.fontawesome.com/158adba575.js"></script>

    <?php $this->head() ?>
</head>
<body class="fon-page">
<?php $this->beginBody() ?>

<?= Alert::widget() ?>
<?php echo $this->render('header'); ?>
        <?= $content ?>
<?php echo $this->render('footer'); ?>

    <div class="tab-bottom flex-line tab-bottom-bg between">
        <button class="text-white active" id="tabBtn1">Профиль</button>
        <button class="text-white" id="tabBtn2">Доступно мне</button>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
