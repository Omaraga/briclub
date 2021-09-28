<?
	/* @var $this yii\web\View */
	$this->title = "Промоушен";
?>

<main class="cours">
    <section class="cours-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="h1 pt-5 pb-5"><?=$this->title?></h1>
                </div>
            </div>
        </div>
    </section>
	<img src="/img/promo.jpg" width="40%" style="margin-right: 50px; margin-left: 50px">
	<img src="/img/rules.jpg" width="40%">
</main>
<?
echo \frontend\components\LoginWidget::widget();
?>