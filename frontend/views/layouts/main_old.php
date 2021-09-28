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

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php $this->registerCsrfMetaTags() ?>
    <!-- Bootstrap -->
    <link href="/css/style.css" rel="stylesheet">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KWLNDDR"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
/*$flashes = Yii::$app->getSession()->getAllFlashes();
if(!empty($flashes)){
    foreach ($flashes as $flash) {*/?><!--
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?/*=$flash*/?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    --><?/*}

}
Yii::$app->getSession()->removeAllFlashes();
*/?>
<?php echo $this->render('header'); ?>
        <?= $content ?>

<?php echo $this->render('footer'); ?>


<?php

$this->registerJs('
$(function(){
    $(\'.modal1\').click(function(e){
        $(".act").text(\'_title_\');
        $("#TV").val(\'_value_\');
    });
    $(\'.modal2\').click(function(e){
        $(".act").text(\'_title_\');
        $("#TV").val(\'_value_\');
    });
    $(\'.modal3\').click(function(e){
        $(".act").text(\'_title_\');
        $("#TV").val(\'_value_\');
    });
    $(\'.modal4\').click(function(e){
        $(".act").text(\'_title_\');
        $("#TV").val(\'_value_\');
    });
    $(\'.modal5\').click(function(e){
        $(".act").text(\'_title_\');
        $("#TV").val(\'_value_\');
    });
    $(\'.modal6\').click(function(e){
        $(".act").text(\'_title_\');
        $("#TV").val(\'_value_\');
    });
});
');
?>

<!-- Include all compiled plugins (below), or include individual files as needed -->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
