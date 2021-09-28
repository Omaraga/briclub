<?php
/* @var $this yii\web\View*/
/* @var $userParent common\models\User*/
/* @var $this yii\web\View*/

$this->registerJsFile('https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidgetDesk&pageLang=ru&widgetTheme=dark&autoMode=false');
?>

<div class="all-page">
    <div class="block-top" style="height: 100px;">
        <div class="container">
            <div class="d-flex pt-3 justify-content-between">
                <div>
                    <img src="/img/academy/ref-logo.svg" alt="">
                </div>
                <div class="d-none d-md-block">
                    <img src="/img/academy/ref-img-group.svg" alt="">
                </div>
            </div>
        </div>
    </div>

    <section class="mt-5" style="display: flex; margin-top: 140px; align-items: center;">
        <div class="container">
            <div style="display: flex; flex-wrap: wrap; align-items: center; margin-top: 68px;">
                <div class="welcome-title">
                    <h3 class="w7">Рады приветствовать вас в LSE platform</h3>
                    <h4 class="welcome-text w5"><span class="text-primary"><?=$userParent['fio'];?></span> рекомендует вам освоить
                        профессию MLM Предприниматель и принять участие в партнерской программе.</h4>
                    <a class="btn yellow border-none w7 mt-5 center" href="/site/signup?referal=<?=$userParent['username'];?>">Зарегистрироваться</a>
                </div>
                <div class="welcome-img-group">
                    <div class="welcome-banner">
                        <div class="welcome-banner_header">
                            <h3 class="w7">MLM Предприниматель</h3>
                            <button class="btn-white">Рекомендуемые</button>
                        </div>
                        <div class="welcome-banner_body">
                            <h5>У нас можно научиться новой профессии или получить новые навыки.
                                Наши программы нацелены на практику, мы следим за актуальностью материал</h5>

                            <div class="welcome_group-list">
                                <ul class="welcome-banner_body-list">
                                    <li><span>Длительность</span></li>
                                    <li><span>Формат обучение</span></li>
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


</div>



