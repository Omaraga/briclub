<?php

/* @var $this yii\web\View */

$this->title = "Мероприятия";
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
    <div class="container">

        <div class="row">
            <? foreach ($news as $course) {
                    $img = $course['link'];

                ?>
                <div class="col-md-4 mb-5">
                    <div class="card">
                        <a href="/events/<?=$course['id']?>"><img width="300" src="<?=$img?>" style="border-radius: 10px;width: 300px" class="card-img-top" alt="..."></a>
                        <div class="card-body">
                            <p class="card-text">
                                <a  href="/events/<?=$course['id']?>"><?=$course['title']?></a>
                            </p>
                            <p class="card-text">
                                <a class="btn btn-success" href="/events/<?=$course['id']?>">Перейти</a>
                            </p>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>
    </div>
</main>
<?
echo \frontend\components\LoginWidget::widget();
?>