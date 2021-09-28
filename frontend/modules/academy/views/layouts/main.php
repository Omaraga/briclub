<?php

/* @var $this yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;

//AppAsset::register($this);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="/img/academy/favicon.svg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css"/>
    <link href="/css/academy_1.css" rel="stylesheet">
    <?=Html::csrfMetaTags();?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <?php $this->registerCsrfMetaTags() ?>

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
<div class="all-page">
<?php echo $this->render('header'); ?>
<?= Alert::widget() ?>
<?= $content ?>

<?php echo $this->render('footer'); ?>

</div>
<?php $this->endBody() ?>

<script type="text/javascript" src="/js/slick.min.js"></script>
<script>
    $(function(){
        let settings = {
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            dots: true
        }
        if(window.screen.width < 1200){
            settings.slidesToShow = 3
        }
        if(window.screen.width < 768){
            settings.slidesToShow = 2
        }
        if(window.screen.width < 575){
            settings.slidesToShow = 1
        }
        $('.slider').slick(settings)
    })
</script>

</body>
</html>
<?php $this->endPage() ?>
