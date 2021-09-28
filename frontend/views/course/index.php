<?
$this->registerCssFile('/css/style_course.css');
$this->title = $course['title'];
?>
<main class="cours">
    <section class="cours-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="h1"><?=$course['title']?></h1>
                </div>
                <div class="col-lg-6 col-md-8 col-12">
                </div>
            </div>
            <div class="info-about-video">
                <span class="info-about-video-item"><span class="price"><?=count($lessons)?> уроков</span></span>
                <span class="info-about-video-item"><span class="name">Длительность:</span><span class="time"><?=$course['duration']?></span></span>
            </div>
        </div>
    </section>

    <section class="cours-program">
        <div class="container">
            <h4 class="h4">Модули</h4>
            <div id="accordion" class="program-accordion">
                <?
                $i = 0;
                foreach ($parts as $part) {
                    $i++;
                    $lessons = \common\models\Lessons::find()->where(['part_id'=>$part['id']])->orderBy("position asc")->all();
                    ?>
                    <div class="card">
                        <div class="card-header" id="heading<?=$i?>">
                            <h5 class="mb-0">
                                <button class="program-title" data-toggle="collapse" data-target="#collapse<?=$i?>" aria-expanded="true" aria-controls="collapse<?=$i?>">
                      <span class="program-title-group">
                          <span class="program-title-group-number"><?=$i?>.</span><span class="program-title-group-nameCourse"><?=$part['title']?></span>
                      </span>
                                    <?if($part['free'] == 1){?>
                                        <span class="status free">Free</span>
                                    <?}?>

                                    <!-- Статусы
                                    <span class="status price">1 909 тенге</span>
                                    <span class="status practise">Практика</span>
                                    <span class="status test">Тест</span>
                                    <span class="status homework">Домашняя работа</span>
                                    -->
                                </button>
                            </h5>
                        </div>

                        <div id="collapse<?=$i?>" class="collapse" aria-labelledby="heading<?=$i?>" data-parent="#accordion">
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <?
                                    $k = 0;
                                    foreach ($lessons as $lesson) {
                                        $k++;
                                        ?>
                                        <?
                                        $link = "/course/view/".$lesson['id'];
                                        if($course['type'] == 5){?>
                                            <?$user_lesson = \common\models\UserLessons::find()->where(['lesson_id'=>$lesson['id'],'user_id'=>Yii::$app->user->id])->one();
                                            if(empty($user_lesson)){
                                                $link = null;
                                            }elseif($user_lesson['status'] == 2){
                                                $link = null;
                                            }
                                        }?>
                                        <li>
                                            <a <?if(!empty($link)){?>href="<?=$link?>"<?}?>><span class="cours-program-number"><?=$i?>.<?=$k?></span><span class="cours-progran-list">
                                                    <?=$lesson['title']?></span>
                                                <?if($lesson['free'] == 1){?>
                                                    <span class="status free">Free</span>
                                                <?}?>
                                                <?

                                                if($course['type'] == 5){?>
                                                    <?$user_lesson = \common\models\UserLessons::find()->where(['lesson_id'=>$lesson['id'],'user_id'=>Yii::$app->user->id])->one();
                                                    if(empty($user_lesson)){?>
                                                        <span class="status danger">Не доступно</span>
                                                    <?}elseif($user_lesson['status'] == 2){
                                                        $seconds = $user_lesson['start'] - time();
                                                        $minutes = floor($seconds / 60);
                                                        $hours = floor($minutes / 60);
                                                        $minutes = $minutes - ($hours * 60);
                                                        ?>
                                                        <span class="status danger"><?=$hours?> ч. <?=$minutes?> м.</span>
                                                    <?}?>

                                                <?}?>
                                            </a>
                                        </li>
                                    <?}?>

                                   <!-- <li><a href="#"><span class="cours-program-number">1.2</span><span class="cours-progran-list">lorem</span></a></li>
                                    <li><a href="#"><span class="cours-program-number">1.3</span><span class="cours-progran-list">lorem</span></a></li>
                                    <li><a href="#"><span class="cours-program-number">1.4</span><span class="cours-progran-list">lorem</span></a></li>
                                    <li><a href="#"><span class="cours-program-number">1.5</span><span class="cours-progran-list">lorem</span> <span class="status homework">Домашняя работа</span></a></li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                <?}?>
            </div>
        </div>
    </section>
</main>
<?
echo \frontend\components\LoginWidget::widget();
?>