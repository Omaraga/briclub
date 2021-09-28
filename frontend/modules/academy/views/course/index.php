<?
/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $courses common\models\Courses */
/* @var $lessons common\models\Lessons */
/* @var $getCourse common\models\Courses*/

$this->title = 'Мое обучение';
?>

<section class="mt-5">
    <div class="container">
        <div class="myCours">
            <div class="myCours_left-bar">
                <div class="banner-user">

                    <div class="header-img center">
                        <h6><?=mb_substr($user['firstname'],0 , 1)?><?=mb_substr($user['lastname'], 0, 1)?></h6>
                    </div>
                    <h5 class="w6"><?=$user['fio']?></h5>
                    <h6><?=$user['email']?></h6>
                    <h5>Логин: <?=$user['username']?></h5>

                    <div class="banner-user_settings">
                        <img src="/img/academy/settings.svg" alt="">
                        <a class="ml-2" href="/academy/profile">Настройки</a>
                    </div>

                </div>

                <div class="banner-balnce fon-grey">
                    <div class="banner-balance_sum">
                        <h6>Баланс</h6>
                        <h2>CV <span><?=$user['w_balans']?></span> </h2>
                    </div>
                    <div class="banner-balance_link">
                        <a class="center" href="/academy/pay">
                            <h6>Пополнить баланс</h6>
                        </a>
                    </div>
                </div>

            </div>

            <div class="myCours_right-bar">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="course-tab" data-toggle="tab" href="#course" role="tab" aria-controls="course" aria-selected="true">Мое обучение</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="certificate-tab" data-toggle="tab" href="#certificate" role="tab" aria-controls="certificate" aria-selected="false">Сертификаты</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="course" role="tabpanel" aria-labelledby="course-tab">
                        <? foreach($courses as $course):?>
                            <div class="welcome_card">
                                <div class="welcome_card-img center <?=($course->icon_color)?$course->icon_color:'violet'?>">
                                    <?if($course['icon_url']):?>
                                        <img src="<?=$course['icon_url']?>" alt="">
                                    <?else:?>
                                        <img src="/img/academy/logo.svg" alt="">
                                    <?endif;?>
                                </div>

                                <div class="welcome_card-main">
                                    <div class="welcome_card-text">
                                        <h4>
                                            <a href="/academy/course/view?id=<?=$course['id']?>"><?=$course['title']?></a>
                                        </h4>
                                        <div>
                                            <p>Прогресс прохождения:</p>
                                            <?
                                            $progress = $course->getCourseProgress($user);
                                            if ($progress['totalLesson']){
                                                $percent = floor($progress['userLesson']/$progress['totalLesson']*100);
                                            }else{
                                                $percent = 0;
                                            }

                                            ?>
                                            <span>Модуль <?=$progress['userModule'];?>/<?=$progress['totalModule'];?></span>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: <?=$percent;?>%" aria-valuenow="<?=$percent;?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <a href="/academy/course/view?id=<?=$course['id']?>"><button type="submit" class="fon-green" >Продолжить</button></a>
                                </div>
                            </div>
                        <? endforeach;?>
                    </div>

                    <div class="tab-pane" id="certificate" role="tabpanel" aria-labelledby="certificate-tab">
                        <div class="moduls">
                            <?foreach ($courses as $course):?>
                                <div class="moduls_header">
                                    <?if($course['icon_url']):?>
                                        <div class="welcome_card-img center violet">
                                            <img src="<?=$course['icon_url']?>" alt="">
                                        </div>
                                    <?else:?>
                                        <img src="/img/academy/logo.svg" alt="" style="width: 10%">
                                    <?endif;?>
                                    <div class="d-flex">
                                        <h5 class="w7">
                                            <?if($course->type == \common\models\Courses::ONLINE_COURSE):?>
                                                Курс:
                                            <?else:?>
                                                Профессия:
                                            <?endif;?>
                                        </h5>
                                        <h5 class="w5"><?=$course['title'];?></h5>
                                    </div>
                                    <h6 class="ml-5">Недоступно</h6>
                                </div>
                            <?endforeach;?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>