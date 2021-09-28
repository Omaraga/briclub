<?
/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $modules common\models\Parts */
/* @var $course common\models\Courses */
/* @var $currLesson common\models\Lessons */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $course->title;

try {
    $this->registerJsFile('/js/course-module.js', ['depends' => 'yii\web\JqueryAsset']);
} catch (\yii\base\InvalidConfigException $e) {

}

?>
<div class="course">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-7">
                <section class="section-video">
                    <? if($currLesson->getFrame()):?>
                        <?=$currLesson->getFrame();?>
                    <?else:?>
                        <img src="<?=$course->getPreview();?>" alt="" style="width: 100%;">
                    <?endif;?>
                    <h4 class="w7 mt-4"><?=Html::encode($currLesson->title); ?></h4>
                    <button class="d-block d-md-none fon-grey btn btn-modul mt-3" href="" >Все модули</button>

                </section>

                <section>
                    <?if(!empty($currLesson['description'])):?>
                        <ul class="description-lessons">
                            <li><h4 class="w8">Описание</h4></li>
                            <li><?=$currLesson['description'];?></li>
                        </ul>
                        <button class="btn-transporent btn">Подробнее</button>
                    <?endif;?>
                </section>

                <?if($lessonDocs = $currLesson->getLessonDocs()):?>
                    <div class="course-material">
                        <h4>Дополнительные материалы</h4>
                        <? foreach ($lessonDocs as $lessonDoc):?>
                            <h6 class="mt-2">
                                <span><?=$lessonDoc->title;?></span>
                                <?if($lessonDoc->link && strlen($lessonDoc->link) > 0):?>
                                    <a class="ml-3" href="<?=$lessonDoc->link?>">Скачать</a>
                                <?elseif($lessonDoc->link2 && strlen($lessonDoc->link2) > 0):?>
                                    <a class="ml-3" href="<?=$lessonDoc->link2?>">Перейти</a>
                                <?endif;?>
                            </h6>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>

            <div class="col-12 col-md-5">
                <? foreach ($modules as $module):?>
                    <div class="mt-4">
                        <ul class="list-courses fon-border">
                            <li>
                                <div class="course-name">
                                    <h5 class="w6"><?=$module['title']?></h5>
                                    <div id="open" style="display: flex;">

                                        <img src="/img/academy/list-plus.svg" alt="">
                                    </div>
                                    <div id="closeBloc" style="display: none;">
<!--                                        <h5 class="w6 mr-5">1/--><?//=$lesson_count?><!--</h5>-->
                                        <img id="closeItem" src="/img/academy/close.svg" alt="">
                                    </div>
                                </div>
                                <ol class="course-item">
                                    <hr>
                                    <? foreach (\common\models\Lessons::getLessonsByPartId($module->id) as $lesson):?>
                                        <li><a href="/academy/course/view?id=<?=$course['id']?>&lessonId=<?=$lesson['id']?>"><h6><?=$lesson['title']?></h6><h6>Доступно</h6></a></li>
                                    <? endforeach;?>
                                </ol>
                            </li>
                        </ul>
                    </div>
                <? endforeach;?>
            </div>


        </div>
    </div>
    <div class="modal-list-courses" style="display: none;">
        <div class="modal-list-courses_header">
            <h6 class="w6">BRI Education</h6>
            <div class="area"><a href="#">
                    <div class="header-img center">
                        <h6>NF</h6>
                    </div>
                </a>
                <ul class="list-click">
                    <li><a href="">Профиль</a></li>
                    <li><a href="">Мой курсы</a></li>
                    <li><a href="">Настройки</a></li>
                    <li><a href="">Выход</a></li>
                </ul>
            </div>
        </div>
        <? foreach ($modules as $module):?>
            <ul class="list-courses fon-border">
                <li class="d-flex click-close-modal-cuorses">
                    <img src="/img/academy/arrow.svg" alt="">
                    <h6 class="ml-4 w6">Видео урок</h6>
                </li>
                <li>
                    <div class="course-name">
                        <h5 class="w6"><?=$module['title']?></h5>
                        <div id="open" style="display: flex;">
                            <img src="/img/academy/list-plus.svg" alt="">
                        </div>
                        <div id="closeBloc" style="display: none;">
<!--                            <h5 class="w6 mr-5">1/--><?//=$lesson_count?><!--</h5>-->
                            <img id="closeItem" src="/img/academy/close.svg" alt="">
                        </div>
                    </div>
                    <ol class="course-item">
                        <hr>
                        <? foreach (\common\models\Lessons::getLessonsByPartId($module->id) as $lesson):?>
                            <li><a href="/academy/course/view?id=<?=$course['id']?>&lessonId=<?=$lesson['id']?>"><h6><?=$lesson['title']?></h6><h6>Доступно</h6></a></li>
                        <? endforeach;?>
                    </ol>
                </li>
            </ul>
        <? endforeach;?>

    </div>

</div>
