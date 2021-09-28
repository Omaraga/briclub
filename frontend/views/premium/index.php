<?php
$this->title = "Premium-аккаунт";
$this->registerJsFile('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',['depends'=>'yii\web\JqueryAsset']);



?>

<main class="pay__premium">
    <div class="container-fluid">

        <div class="row justify-content-center qwerty" >
            <div class="col-9 text-center">
                <h1 class="h2 w7">Выберите удобный тариф для вашего заработка</h1>
                    <p class="">Премиум Аккаунт – уникальная возможность повысить ваши доходы за счет нового эксклюзивного функционала, открывающие двери в мир новых возможностей!</p>
            </div>



            <div class="row flex-wrap-reverse" style="justify-content: center;">
                <div class="block__size col-12 col-md-8 col-lg-5">
                    <div class="btn__base">
                        <i class="fas fa-user"></i>
                        <span>Базовый</span>
                    </div>
                    <ul class="list">
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Доступ к Библиотеке</span>
                        </li>
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Тех поддержка</span>
                        </li>
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Мои счета</span>
                        </li>
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Промо-материалы</span>
                        </li>
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Новости</span>
                        </li>
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Интернет магазин</span>
                        </li>
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Доска объявлении</span>
                        </li>
                        <li class="item">
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span class="gray">Блокировка места в структуре начиная с третьего стола</span>
                        </li>
                    </ul>
                    <div class="info__text">
                        <span class="w7 p">Вам уже доступен базовый тариф</span>
                    </div>
                </div>

                <div class="block__size block__fon col-12 col-md-8 col-lg-5">
                    <div class="btn__premium">
                        <img src="/img/pay__premium/coron.svg" alt="">
                        <span class="w5">Premium</span>
                    </div>
                    <ul class="list">
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Функции Базового тарифа</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Статистика доходов</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Статистика структуры</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Статистика матрицы</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Неограниченный доступ к видео материалам</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Приоритетная техподдержка</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Добавление аватара на профиль аккаунта</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Новая темная тема</span>
                        </li>
                        <li>
                            <div class="checkbox"><img src="/img/pay__premium/Vector.svg" alt=""></div>
                            <span>Блокировка места в структуре начиная со второго стола</span>
                        </li>
                    </ul>
                    <ul class="nav nav-pills my-3" id="pills-tab" role="tablist" style="justify-content: center;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><?=$tariffs[0]->name?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><?=$tariffs[1]->name?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"><?=$tariffs[2]->name?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-two" role="tab" aria-controls="pills-contact" aria-selected="false"><?=$tariffs[3]->name?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-three" role="tab" aria-controls="pills-contact" aria-selected="false"><?=$tariffs[4]->name?></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-four" role="tab" aria-controls="pills-contact" aria-selected="false"><?=$tariffs[5]->name?></a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <a href="/premium/buy?id=1" style="text-decoration: none;">
                                <div class="btns__grups">
                                    <span class="hop w7">Купить подписку за $<?=$tariffs[0]->price?></span>
                                </div>
                            </a>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-home-tab">
                            <a href="/premium/buy?id=2" style="text-decoration: none;">
                                <div class="btns__grups">
                                    <span class="hop w7">Купить подписку за $<?=$tariffs[1]->price?></span>
                                </div>
                            </a>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-home-tab">
                            <a href="/premium/buy?id=3" style="text-decoration: none;">
                                <div class="btns__grups">
                                    <span class="hop w7">Купить подписку за $<?=$tariffs[2]->price?></span>
                                </div>
                            </a>
                        </div>
                        <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-home-tab">
                            <a href="/premium/buy?id=4" style="text-decoration: none;">
                                <div class="btns__grups">
                                    <span class="hop w7">Купить подписку за $<?=$tariffs[3]->price?></span>
                                </div>
                            </a>
                        </div>
                        <div class="tab-pane fade" id="pills-three" role="tabpanel" aria-labelledby="pills-home-tab">
                            <a href="/premium/buy?id=5" style="text-decoration: none;">
                                <div class="btns__grups">
                                    <span class="hop w7">Купить подписку за $<?=$tariffs[4]->price?></span>
                                </div>
                            </a>
                        </div>
                        <div class="tab-pane fade" id="pills-four" role="tabpanel" aria-labelledby="pills-home-tab">
                            <a href="/premium/buy?id=6" style="text-decoration: none;">
                                <div class="btns__grups">
									<span class="hop w7">Купить подписку за $<?=$tariffs[5]->price?></span>
                                </div>
                            </a>
                        </div>

                    </div>

                </div>
            </div>


        </div>
    </div>
</main>

