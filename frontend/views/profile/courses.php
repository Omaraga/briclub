<?php

/* @var $this yii\web\View */



$this->title = "Мои курсы";
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
?>
<main class="cours">
    <section class="cours-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="h1"><?=$this->title?></h1>
                </div>
            </div>
        </div>
    </section>
    <div class="container">

        <div class="row">
            <? foreach ($courses as $course) {
                $course = \common\models\Courses::findOne($course['id']);
                if(empty($course)) continue;
                $screens = \common\models\CourseScreens::find()->where(['course_id'=>$course['id'],'is_active'=>1,'screen_id'=>2])->orderBy('position asc')->all();
                foreach ($screens as $screen) {
                    $des = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>8])->one()['title'];
                    $img = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>9])->one()['link'];
                }
                ?>
                <div class="col-md-4 mb-5">
                    <div class="card">
                        <img src="<?=$img?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <a href="/<?=$course['alias']?>"><h5 class="card-title"><?=$course['title']?></h5></a>
                            <p class="card-text"><?=$des?></p>
                            <p class="card-text">
                                <span class="info-about-video-item"><span class="name">Длительность:</span><span class="time"><?=$course['duration']?></span></span>
                            </p>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>
    </div>
</main>
<?
echo \frontend\components\SignupWidget::widget();
echo \frontend\components\LoginWidget::widget();
?>