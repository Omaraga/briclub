<?
/* @var $this yii\web\View
 * @var $course common\models\Courses
 * @var $modules common\models\Parts
 * @var $lesson common\models\Lessons
 * @var $user common\models\User;
 * @var $moduleIntro common\models\Parts
 * */
use common\models\UserCourses;
use yii\helpers\Url;
use common\models\Courses;

$this->title = $course->title;

try {
    $this->registerJsFile('/js/course-module.js', ['depends' => 'yii\web\JqueryAsset']);
} catch (\yii\base\InvalidConfigException $e) {

}
?>
<div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7">
                <section class="section-video-course">
                    <div>
                        <?if($lesson && $lesson->getFrame()):?>
                            <?=$lesson->getFrame()?>
                        <?else:?>
                            <img src="<?=$course->getPreview();?>" alt="" style="width: 100%;">
                        <?endif;?>

                        <h3 class="w7 my-3"><?=$course['title']?></h3>

                        <div class="d-flex mt-4 box-sum">
                            <?if($user && UserCourses::isAccess($user->id, $course->id)):?>
                                <a class="fon-green center" href="/academy/course/view?id=<?=$course['id']?>">Продолжить</a>
                                <div class="ml-2">
                                    <?if($course['price']):?>
                                        <h3 class="w8">CV <?=$course['price']?></h3>
                                    <?else:?>
                                        <h3 class="w8">Бесплатно</h3>
                                    <?endif;?>
                                    <h6><?=$course['title']?></h6>
                                </div>
                            <?else:?>
                                <?if($course->soon == 1):?>
                                    <a class="fon-green center" href="#">Скоро</a>
                                    <div class="ml-2">
                                        <h3 class="w8">CV --</h3>
                                    </div>
                                    <h6><?=$course['title']?></h6>
                                <?else:?>
                                    <a class="fon-green center" href="buy<?=($course['price'] > 0)?'':'free';?>?id=<?=$course['id']?>">
                                        <?=($course['price'] > 0)?'Купить':'Получить';?>
                                    </a>
                                    <div class="ml-2">
                                        <?if($course['price']):?>
                                            <h3 class="w8">CV <?=$course['price']?></h3>
                                        <?else:?>
                                            <h3 class="w8">Бесплатно</h3>
                                        <?endif;?>
                                        <h6><?=$course['title']?></h6>
                                    </div>
                                <?endif;?>
                            <?endif;?>
                        </div>
                    </div>
                    <?if($course['type'] == Courses::MLM):?>
                        <div class="profile-card">
                            <div>
                                <h3 class="w7 mb-4">Страховка в подарок</h3>
                                <div>
                                    <span class="w5">на</span>
                                    <span class="w7 profile-card_text-title">1 год</span>
                                    <p class="w5">Страховой полис </p>
                                </div>
                            </div>
                            <div class="drop">
                                <img src="/img/academy/drop.svg" alt="">
                            </div>
                        </div>
                    <?endif;?>
                </section>

                <section class="section-description">
                    <h3 class="w7">Описание</h3>

                    <div class="course-panel welcome-panel">
                        <div class="my-4">
                            <?if($course->type == Courses::ONLINE_COURSE):?>
                                <a class="fon-border-line">Курс</a>
                            <?else:?>
                                <a class="fon-border-line">Профессия</a>
                            <?endif;?>
                        </div>
                        <div class="my-4">
                            <span class="span-blue"><?=$course->duration;?></span>
                            <span class="span-blue">Онлайн</span>
<!--                            <span class="span-blue">Русский</span>-->
                            <span class="span-blue">Сертификат</span>
                        </div>
                    </div>

                    <h6 class="w5"><?=$course['description']?></h6>

                    <?if($course['type'] == Courses::MLM):?>
                        <div class="shanurak-link">
                            <div class="shanurak-link_header">
                                <img src="/img/academy/carbon_quotes.svg" alt="" class="mr-3">
                                <h4 class="w7">Партнерская программа</h4>
                            </div>
                            <div class="shanurak-link_body">
                                <h6 class="w5">Партнерская программа дает вам возможность сразу же, практиковать полученные теоретические знания и развивать ваши навыки продаж.</h6>
                            </div>
                        </div>
                    <?endif;?>
                </section>

                <section class="mt-5">
                    <h3 class="mb-5">Программа <?=($course->type == Courses::ONLINE_COURSE)?'курса':'профессии';?></h3>
                    <? foreach ($modules as $module):?>
                        <ul class="list-courses fon-border">
                            <li>
                                <div class="course-name">
                                    <h5 class="w6"><?=$module['title']?></h5>
                                    <div id="open" style="display: flex;">

                                        <img src="/img/academy/list-plus.svg" alt="">
                                    </div>
                                    <div id="closeBloc" style="display: none;">
                                        <img id="closeItem" src="/img/academy/close.svg" alt="">
                                    </div>
                                </div>
                                <ol class="course-item">
                                    <hr>
                                    <? foreach (\common\models\Lessons::getLessonsByPartId($module->id) as $lesson):?>
                                        <li><h6><?=$lesson['title']?></h6></li>
                                    <? endforeach;?>
                                </ol>
                            </li>
                        </ul>
                    <? endforeach;?>
                </section>

            </div>

            <?if($moduleIntro):?>

                <div class="col-12 col-lg-5">
                    <div class="">
                        <ul class="profile-list section-video-course">
                                <? foreach (\common\models\Lessons::getLessonsByPartId($moduleIntro->id) as $lesson):?>
                                    <li>
                                        <a href="<?=Url::to(['/academy/course/about','id' => $course->id, 'lessonId' => $lesson->id]);?>">
                                            <div>
                                                <div class="profile-list_title">
                                                    <img src="/img/academy/profil-play.svg" alt="">
                                                    <span class="w7">Вводный урок</span>
                                                </div>
                                                <p><?=$lesson['title']?></p>
                                            </div>
                                            <img src="<?=$lesson['image_url']?>" alt="" width="100px" height="100px">
                                        </a>
                                    </li>
                                <? endforeach;?>
                        </ul>
                    </div>
                </div>
            <?endif;?>
        </div>
    </div>
</div>

