<?php

/* @var $this yii\web\View */

$this->title = "Все курсы";
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
            <? foreach ($courses as $course) {
                //$user_course = \common\models\UserCourses::find()->where(['user_id'=>Yii::$app->user->id,'course_id'=>$course['id']])->one();
                //if(empty($user_course)) continue;
                $screens = \common\models\CourseScreens::find()->where(['course_id'=>$course['id'],'is_active'=>1,'screen_id'=>2])->orderBy('position asc')->all();
                foreach ($screens as $screen) {
                    $des = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>8])->one()['title'];
                    $img = \common\models\Content::find()->where(['screen_course_id'=>$screen['id'],'type'=>9])->one()['link'];
                }
                ?>
                <div class="col-md-4 mb-5">
                    <div class="card">
                        <a href="/course/<?=$course['id']?>"><img src="/img/courses/eng.jpg" class="card-img-top" alt="..."></a>
                        <div class="card-body">
                            <p class="card-text">
                                <span class="info-about-video-item"><span class="time badge badge-primary"><?=$course['duration']?></span></span>
                                <?if($course['soon']==1){?>
                                    <span class="badge badge-success">Скоро</span>
                                <?}?>
                            </p>

                            <?if(!empty($des)){?>
                                <p class="card-text lead"><?=$des?></p>
                            <?}?>

                            <?if(!empty($course['certificate'])){?>
                                <p class="card-text"><?=$course['certificate']?></p>
                            <?}?>


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