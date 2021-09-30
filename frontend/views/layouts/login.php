<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
$this->registerJsFile('/js/mobile.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/js/fa-fa.js',['depends'=>'yii\web\JqueryAsset']);
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title><?= Html::encode($this->title) ?></title>
        <link href="/css/signup.css" rel="stylesheet">
        <?php $this->head() ?>

    </head>
    <body>
    <style>
        body {
            background: url(/img/register/reg-img.svg)no-repeat center;
            background-size: cover;
        }
        .form-control{
            width: 100%;
            height: 40px;
            border-radius: 4px;
            padding-left: 16px;
            background: #292344;
            color: #fff;
            border: 1px solid #292344;
        }
        .form-control:focus{
            border: 1px solid rgba(84, 96, 245, 0.29);
        }
        .password-control {
        //position: absolute;
            top: 11px;
            right: 6px;
            display: inline-block;
            width: 20px;
            height: 20px;
            background: url(/img/view.svg) 0 0 no-repeat;
            float: right;
            margin-top: -29px;
            margin-right: 8px;
        }
        .password-control.view {
            background: url(/img/no-view.svg) 0 0 no-repeat;
        }
        .form-check label{
            cursor: pointer;
            margin-top: -3px;
        }
        .error{
            border: 1px solid #ced4da;
        }
        .errorText, .help-block{
            color:red;
            font-size: 0.8rem;
        }
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
    <div class="center">
        <img src="/img/logo.svg" alt="">
    </div>

    <?= $content ?>

    <?php echo $this->render('footer'); ?>


    <?php $this->endBody() ?>
    <script src="https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidgetDesk&pageLang=ru&widgetTheme=light&autoMode=false"></script>
    </body>
    </html>
<?php $this->endPage() ?>