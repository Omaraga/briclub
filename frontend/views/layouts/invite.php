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

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php $this->registerCsrfMetaTags() ?>
    <!-- Bootstrap -->

    <link rel="stylesheet" href="">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title><?= Html::encode($this->title) ?></title>
    <link href="/css/academy_1.css" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?php echo $this->render('footer'); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
