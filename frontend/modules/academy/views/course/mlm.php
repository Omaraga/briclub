<?
/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $modules common\models\Parts[] */
/* @var $course common\models\Courses */
/* @var $currLesson common\models\Lessons */
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Lessons;
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
                    <? if($currLesson && $currLesson->getFrame() ):?>
                        <?=$currLesson->getFrame();?>
                    <?else:?>
                        <img src="<?=$course->getPreview();?>" alt="" style="width: 100%;">
                    <?endif;?>
                    <h4 class="w7 mt-4"><?=Html::encode($currLesson->title); ?></h4>

                </section>
                <div class="shanurak-link">
                    <div class="shanurak-link_header">
                        <img src="/img/academy/carbon_quotes.svg" alt="" class="mr-3">
                        <h4 class="w7">Партнерская программа</h4>
                    </div>
                    <div class="shanurak-link_body">
                        <h6 class="w5">Партнерская программа дает вам возможность сразу же, практиковать полученные теоретические знания и развивать ваши навыки продаж.</h6>

                        <div class="mt-4">
                            <?if($user->is_agree_contract == 0):?>
                                <a class="btn btn-blue" data-toggle="modal" data-target="#contractModal" href="#">
                                    Перейти
                                </a>
                            <?else:?>
                                <a class="btn btn-blue" href="/profile" >Перейти</a>
                            <?endif;?>
                        </div>

                    </div>
                </div>
                <section>
                    <?if(!empty($currLesson['description'])):?>
                        <ul class="description-lessons">
                            <li><h4 class="w8">Описание</h4></li>
                            <li id="description"><?=$currLesson['description'];?></li>
                        </ul>
                        <button class="btn-transporent btn" onClick="uncoverDescription()">Подробнее</button>
                    <?endif;?>
                </section>

                <?if($currLesson && $lessonDocs = $currLesson->getLessonDocs()):?>
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
                                        <?$isAvailable = $lesson->isLessonAvailable($user);?>
                                        <?if($isAvailable == Lessons::LESSON_AVAILABLE):?>
                                            <li>
                                                <a href="/academy/course/mlm?id=<?=$course['id']?>&lessonId=<?=$lesson['id']?>">
                                                    <h6><?=$lesson['title']?></h6>
                                                    <h6>Завершен</h6>
                                                </a>
                                            </li>
                                        <?elseif ($isAvailable == Lessons::LESSON_NEED_BUY):?>
                                            <li>
                                                <a href="/academy/course/buy?id=<?=$course['id']?>&level=<?=$module->getModulePosition()?>">
                                                    <h6><?=$lesson['title']?></h6>

                                                    <button class="btn fon-green">CV <?=$lesson->getPrice();?></button>
                                                </a>
                                            </li>
                                        <?else:?>
                                            <li>
                                                <a href="/academy/course/mlm?id=<?=$course['id']?>&lessonId=<?=$lesson['id']?>">
                                                    <h6><?=$lesson['title']?></h6>
                                                    <h6>Бонус</h6>
                                                </a>
                                            </li>
                                        <?endif;?>
                                    <? endforeach;?>
                                </ol>
                            </li>
                        </ul>
                    </div>
                <? endforeach;?>
            </div>
        </div>
    </div>
</div>
<script>
    const description = document.getElementById('description')
    let str = description.innerHTML.substr(0, 2000)
    description.innerHTML = str
                                            
    function uncoverDescription() {
        str= description.innerHTML.substr(0, 2000)
    }
    console.log(description.innerHTML)
</script>
