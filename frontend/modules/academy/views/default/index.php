<?php
/* @var $this yii\web\View*/
/* @var $user common\models\User*/
/* @var $professions common\models\Courses[]*/
/* @var $courses common\models\Courses[]*/
/* @var $reviews common\models\CourseReviews[]*/
use common\models\Courses;
$this->title = 'LSE';
?>
<main>
    <?if(Yii::$app->user->isGuest):?>
        <section>
            <div class="container">
                <div class="section-welcome">
                    <div class="welcome-title">
                        <h1>Образовательная платформа</h1>
                        <h4 class="welcome-text w1">На наших курсах вы можете найти все для успешного профессионального будущего.
Можете прокачать себя в разных направлениях, обучиться новой профессии, и приобрести разные скилы.</h4>
                    </div>
                    <div class="welcome-img-group">
                        <div class="welcome-img"></div>
                    </div>
                </div>
            </div>
        </section>

    <?elseif($actualCourse = Courses::getActualCourse($user)):?>
        <section>
            <div class="container">
                <div class="welcome-card-name">
                    <div class="welcome-lesson">
                        <h2 class="w7"><?=$user->firstname;?>, добро пожаловать</h2>
                        <h4 class="mt-2">Продолжайте свое обучение</h4>

                        <div class="welcome_card">
                            <div class="welcome_card-img <?=$actualCourse->icon_color;?> center">
                                <img src="<?=$actualCourse->getIcon();?>" alt="" style="width: 60%">
                            </div>
                            <div class="welcome_card-main">
                                <div class="welcome_card-text">
                                    <h4><?=$actualCourse->title;?></h4>
                                    <?
                                        $progress = $actualCourse->getCourseProgress($user);
                                        if ($progress['totalLesson'] > 0){
                                            $percent = floor($progress['userLesson']/$progress['totalLesson']*100);
                                        }
                                    ?>
                                    <div>
                                        <span>Прогресс прохождение:</span>
                                        <span>Модуль <?=$progress['userModule'];?>/<?=$progress['totalModule'];?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: <?=$percent;?>%" aria-valuenow="<?=$percent;?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <h6 class="ml-4"><?=$percent;?>%</h6>
                                    </div>
                                </div>

                                <a class="" href="/academy/course/view?id=<?=$actualCourse->id;?>">
                                    <button class="fon-green">Продолжить</button>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    <?else:?>
        <section>
            <div class="container">
                <div class="section-welcome">
                    <div class="welcome-title">
                        <h1>С чего начать ?</h1>
                        <h4 class="welcome-text w1">У нас есть все для успешного профессионального будущего.
                            Большие курсы и короткие интенсивы, которые помогут в работе и жизни.</h4>
                        <a class="btn fon-grey mt-5 center" href="/academy/course/about?id=<?=Courses::getMlm()->id?>">Подробнее</a>
                    </div>
                    <div class="welcome-img-group">
                        <div class="welcome-banner">
                            <div class="welcome-banner_header">
                                <h3 class="w7">MLM Практикум</h3>
                                <button class="btn-white">Рекомендуемые</button>
                            </div>
                            <div class="welcome-banner_body">
                                <h5>У нас можно научиться новой профессии или получить новые навыки.
                                    Наши программы нацелены на практику, мы следим за актуальностью материал</h5>

                                <div class="welcome_group-list">
                                    <ul class="welcome-banner_body-list">
                                        <li><span>Длительность</span></li>
                                        <li><span>Формат обучения</span></li>
                                        <li><span>Практика</span></li>
                                    </ul>
                                    <ul>
                                        <li><span class="w6">12 месяцев</span></li>
                                        <li><span class="w6">Онлайн</span></li>
                                        <li><span class="w6">Предусмотрена</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?endif;?>



    <section class="bg-light pb-5 pt-5">
        <div class="title">
            <div class="container">
                <h3 class="w8">Профессии</h3>
                <div class="title-group">
                    <h5 class="title-text">С нами вы изучите самые востребованные профессии и сможете повысить квалификацию по
                        востребованным направлениям.Обучаем эффективным и гибким навыкам.</h5>

                    <div class="title-line">
                        <div class="yellow-circle center">
                            <div class="circle"></div>
                        </div>
                        <button class="btn-radius fon-transparent center">LSE Platform</button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="container">
                <div class="list-professions">
                    <?foreach ($professions as $profession):?>
                        <a href="/academy/course/about?id=<?=$profession->id?>">
                            <div class="card">
                                <div>
                                    <div class="card_img <?=($profession->icon_color)?$profession->icon_color:'violet'?> center">
                                        <img src="<?=$profession->getIcon();?>" style="max-width: 100%" alt="">
                                    </div>
                                </div>
                                <div class="card_text-group">
                                    <h5 class="w8 card_text"><?=$profession->title;?></h5>
                                    <p>LSE Platform</p>
                                    <div class="mt-4">
                                        <span><?=$profession->duration?></span>
                                        <span>Онлайн</span>
                                    </div>
                                </div>
								<?=($user && $profession == Courses::getActualCourse($user))? '' : '<h4 class="card_sum w8">'.$profession->getPrice().'</h4>'?>

                              
                            </div>
                        </a>
                    <?endforeach;?>


                </div>
            </div>
        </div>
    </section>

    <section class="bg-light">
        <div class="container">
            <h3 class="w8">Онлайн курсы</h3>
            <div class="list-professions">
                <?foreach ($courses as $course):?>
                    <a <?=($course->soon == 1)? 'class="disabled"' : 'href="/academy/course/about?id='.$course->id.'"'?>>
                        <div class="card">
                            <div>
                                <div class="card_img <?=($course->icon_color)?$course->icon_color:'violet'?> center">
                                    <img src="<?=$course->getIcon();?>" style="max-width: 100%" alt="">
                                </div>
                            </div>
                            <div class="card_text-group">
                                <h5 class="w8 card_text"><?=$course->title;?></h5>
                                <p>LSE Platform</p>
                                <div class="mt-4">
                                    <span><?=$course->duration?></span>
                                    <span>Онлайн</span>
                                </div>
                            </div>
                            <h4 class="card_sum w8"><?=$course->getPrice();?></h4>
                        </div>
                    </a>
                <?endforeach;?>


            </div>
        </div>
    </section>
    <?if(Yii::$app->user->isGuest):?>
    <section>
        <div class="title">
            <div class="container">
                <h3 class="w8">Чему мы учим</h3>
                <div class="title-group">
                    <h5 class="title-text">Освоить современную востребованную специальность с нуля. 
                        Получить новые навыки в профеcсии. Быть востребованным и актуальным. </h5>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="banner-group">
                <div class="block-one center">
                    <div>
                        <h1>3</h1>
                        <h3>Профессии</h3>
                    </div>
                </div>
                <div class="block-two center violet">
                    <h4>У нас можно освоить новую профессию с нуля</h4>
                </div>
                <div class="block-three center">
                    <div>
                        <h1>300 +</h1>
                        <h3>Видеоуроков</h3>
                    </div>
                </div>
                <div class="block-four yellow center">
                    <h4>Вы можете найти то, что подходит вам</h4>
                </div>
            </div>
            <div class="banner-group">
                <div class="block-five center">
                    <div>
                        <h1>25 +</h1>
                        <h3>Курсов</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section>
        <div class="title">
            <div class="container">
                <h3 class="w8">Нас уже 30 000 +</h3>
                <div class="title-group">
                    <h5 class="title-text">и мы работаем для увеличения качества вашего образования</h5>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <div class="container">
                <div class="slider">
                    <?foreach ($reviews as $review):?>
                        <div class="slider_user-block">
                            <div class="user-block_img">
                                <img src="<?=$review->img_url?>" alt="">
                            </div>
                            <div class="user-block_name ">
                                <h5 class="w6"><?=$review->name?></h5>
                                <p><?=Courses::getCourseTitle($review->course_id)?></p>
                            </div>
                            <p><?=$review->content?></p>
                        </div>
                    <?endforeach;?>


                </div>
            </div>
        </div>
    </section>

<!--    <section>-->
<!--        <div class="container">-->
<!--            <div class="banner-form yellow">-->
<!--                <div class="banner-form-title">-->
<!--                    <h2 class="w7">Мы всегда готовы помочь!</h2>-->
<!--                    <h4 class="banner-form_text">Если у вас есть вопросы о формате или вы не знаете что выбрать, оставьте свой номер: мы позвоним, чтобы ответить на все ваши вопросы.</h4>-->
<!--                    <img src="./img/welcome/Group 1.svg" alt="">-->
<!--                </div>-->
<!--                <form action="">-->
<!--                    <div>-->
<!--                        <input type="text" placeholder="Имя">-->
<!--                        <input type="text" placeholder="Номер телефона">-->
<!--                    </div>-->
<!--                    <button class="btn btn-dark">Отправить</button>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
    <?endif;?>
</main>