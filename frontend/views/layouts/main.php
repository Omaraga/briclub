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
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php $this->registerCsrfMetaTags() ?>
    <!-- Bootstrap -->

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="/css/style2_6.css" rel="stylesheet">
    <?if(Yii::$app->session->get('theme') == 0):?>
        <link href="/css/style_new_3_0.css" rel="stylesheet">
    <?else:?>
        <link href="/css/style_dark.css" rel="stylesheet">
    <?endif;?>
    <title><?= Html::encode($this->title) ?></title>
	<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
		   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
		   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
		   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

		   ym(73211866, "init", {
				clickmap:true,
				trackLinks:true,
				accurateTrackBounce:true,
				webvisor:true
		   });
		</script>
        <script src="https://kit.fontawesome.com/baa51cad6c.js" crossorigin="anonymous"></script>
		<noscript><div><img src="https://mc.yandex.ru/watch/73211866" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php echo $this->render('header'); ?>
        <?= $content ?>

<?php echo $this->render('footer'); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
