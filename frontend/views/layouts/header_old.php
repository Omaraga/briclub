<?
    $courses = \common\models\UserCourses::find()->where(['user_id'=>Yii::$app->user->identity['id']])->all();
    $all_courses = \common\models\Courses::find()->all();
?>
<main class="display-nav">
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand mr-auto" href="/"><img width="67" src="/img/logo.svg" alt=""></a>

            <!--
                        <button class="navbar-toggler search-navbar-btn mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <img src="/img/searchicons.svg" alt="">
                        </button>
            -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- visibel cat only desktop -->
                <ul class="navbar-nav ml-lg-5">
                    <li class="nav-item dropdown ml-lg-2 cat-item-desktop">
                        <a class="nav-link" href="/courses/list" id="navbarDropdown" role="button"  aria-haspopup="true" aria-expanded="false">
                            <img src="/img/cat.svg" class="pr-1" alt=""> Все курсы
                        </a>
                    </li>
                </ul>
                <!-- visibel cat only desktop -->

                <form class="form-inline form-search mr-auto ml-lg-5 hidden" >
                    <input class="form-control" type="search" placeholder="Ищите любой курс или видео" aria-label="Search">
                    <button class="btn btn-search" type="submit"><img src="/img/searchicons.svg" alt=""></button>
                </form>

                <ul class="navbar-nav sign-in-toolbar">
                    <!-- visibel cat only mobile -->
                    <li class="nav-item dropdown ml-lg-2 mr-4 mr-lg-0 cat-item-mobile">
                        <a class="nav-link" href="/courses/list" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="/img/cat.svg" class="pr-1" alt=""> Все курсы
                        </a>
                    </li>
                    <!-- visibel cat only mobile -->
                    <?php
                    if(Yii::$app->user->isGuest){
                    ?>
                        <li class="nav-item">
                            <a href="/site/login" class="btn btn-outline-primary my-2 my-sm-0" >Войти</a>
                        </li>
                    <?php }else{?>


                    <li class="nav-item dropdown">
                        <!--<a class="nav-link" href="/profile/courses" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">Мои курсы</a>-->
                    </li>
                    <li class="nav-item ml-4">
                        <a class="nav-link" href="#" role="button" aria-haspopup="true" aria-expanded="false"><img src="/img/heart.svg" alt=""></a>
                    </li>
                    <li class="nav-item dropdown avatar ml-4">
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="avatar-name avatar-bg-1"><?=strtoupper(Yii::$app->user->identity['email']{0})?></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/profile">Профиль</a>
                            <!--<a class="dropdown-item" href="/profile/courses">Мои курсы</a>-->
                            <a class="dropdown-item" href="/profile/settings">Настройки</a>
                            <div class="dropdown-divider"></div>
                            <?php echo \yii\helpers\Html::a('Выйти','/site/logout',['data-method'=>'POST','class'=>'dropdown-item']); ?>
                        </div>
                    </li>
                    <?php }?>
                </ul>

            </div>
        </nav>
    </header>
</main>