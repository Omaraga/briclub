<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
$this->registerJsFile('/js/mobile.js',['depends'=>'yii\web\JqueryAsset']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php $this->registerCsrfMetaTags() ?>
    <!-- Bootstrap -->

    <link rel="stylesheet" href="">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title><?= Html::encode($this->title) ?></title>
    <link href="/css/style_new_2_0.css" rel="stylesheet">
    <?php $this->head() ?>

</head>
<body>
<style>
    .bab{
        position: absolute;
        right: 0;
    }
    .bab i{
        min-width: 2.75rem;
        min-height: 2.75rem;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 1rem;
        color: #838383;
        font-size: 1.3rem;
    }
    #yt-widget.yt-state_mobile .yt-listbox__text{
        text-align: center;
    }
    header #yt-widget .yt-listbox {
        position: fixed;
    }
    .yt-wrapper_align_right{
        display: none !important;
    }
</style>
<?php $this->beginBody() ?>
<div class="bab d-flex center-block">
    <div id="ytWidgetDesk">

    </div>
</div>

        <?= $content ?>

<?php echo $this->render('footer'); ?>


<?php $this->endBody() ?>
<script src="https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidgetDesk&pageLang=ru&widgetTheme=light&autoMode=false"></script>
</body>
</html>
<?php $this->endPage() ?>
