<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php $this->registerCsrfMetaTags() ?>
    <!-- Bootstrap -->
<!--    <link href="/css/style2_6.css" rel="stylesheet">-->
<!--    <link href="/css/style_dark.css" rel="stylesheet">-->
<!--    <title>--><?//= Html::encode($this->title) ?><!--</title>-->
<!--    --><?//if(Yii::$app->session->get('theme') == 0):?>
        <link href="/css/style_new_2_0.css" rel="stylesheet">
<!--    --><?//else:?>
<!--        <link href="/css/style_dark.css" rel="stylesheet">-->
<!--    --><?//endif;?>
    <link rel="stylesheet" href="/css/style_error-404.css">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php echo $this->render('header_error'); ?>
<?= $content ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
