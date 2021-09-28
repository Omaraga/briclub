<?

use yii\helpers\Html;
if (Yii::$app->user->isGuest){
    $user = null;
}else{
    $user = Yii::$app->user->identity;
}
$is_agent = null;
$agent_status = null;
if(!empty($user)){
    if ($user['is_agent'] == 1){
        $is_agent = true;
        $agent_status = $user['agent_status'];
    }
}
$script = <<<JS
    var isOpen = false
    $('.header-img').click(function (e){
        e.preventDefault()
        console.log('hello')
        if (!isOpen){
            
            isOpen = true
            $('.list-click').show()
        }else{
            isOpen = false
            $('.list-click').hide()
        }
    })
    $('#header-close').on('click',function() {
        $('body').css({
        overflow: 'scroll'
      })
      $('#header-close').css({
        display : 'none'
      })
      $('.menu-colaps').css({
        right : -99999,
        display: 'none'
      })
      $('.navbar-toggler-icon').css({
        display : 'block'
      })
    })
    $('#header-open').on('click',function() {
      $('#header-close').css({
        display : 'block'
      })
      $('body').css({
        overflow: 'hidden'
      })
      $('.menu-colaps').css({
        right : 0 ,
        display : 'block'
      })
      $('.navbar-toggler-icon').css({
        display : 'none'
      })
    })
JS;

$this->registerJs($script);
$this->registerJsFile('/js/mobile.js',['depends'=>'yii\web\JqueryAsset']);
?>
<style>
    .list-click{
        display: none;
    }
</style>
<header>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="/"><img src="/img/academy/logo.svg" alt=""></a>
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-icon" id="header-open"></span>
                <img id="header-close" src="/img/academy/header-close.svg" alt="" style="display: none;">
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                        <li><div class="bab">
                                <div id="ytWidgetDesk">

                                </div>
                            </div></li>
                    <?if($user):?>
                        <li><a href="/academy/course">Мое обучение</a></li>
						<?if($is_agent && $agent_status == \backend\models\AgentForm::ACTIVE):?>
                                <li><a href="/profile/transfer">Переводы</a></li>
                        <?endif;?>
                        <li><a href="/">Все курсы</a></li>
                        <li class="area"><a href="#">
                                <div class="header-img center">
                                    <h6><?=mb_substr($user['firstname'],0 , 1)?><?=mb_substr($user['lastname'], 0, 1)?></h6>
                                </div>
                            </a>
                            <ul class="list-click">
                                <?if ($user->activ == 1):?>
                                    <?if($user->is_agree_contract == 0):?>
                                        <a data-toggle="modal" data-target="#contractModal" href="#">
                                            Партнерская программа
                                        </a>
                                    <?else:?>
                                        <li><a href="/profile">Партнерская программа</a></li>
                                    <?endif;?>

                                <?endif;?>
								<?if($is_agent && $agent_status == \backend\models\AgentForm::ACTIVE):?>
                                    <li><a href="/profile/transfer">Переводы</a></li>
                                <?endif;?>
                                <li><a href="/academy/course">Профиль</a></li>
                                <li><a href="/academy/profile">Настройки</a></li>
                                <li>
                                    <form action="/site/logout" method="post" >
                                        <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
                                        <button class="fon-transparent" type="submit">Выход</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?else:?>
                        <li>
                            <div class="authorization">
                                <a href="/site/login" class="btn yellow">Вход</a>
                                <a href="/site/signup" class="btn fon-transparent">Регистрация</a>
                            </div>
                        </li>
                    <?endif;?>
                </ul>
            </div>
        </nav>
    </div>
</header>

    <div class="menu-colaps">
      <div class="container">
        <div class="menu-colaps_header">

        </div>
        <ul class="menu-colaps_body">
        <?if($user):?>
            <?if ($user->activ == 1):?>
                <li>
                <?if($user->is_agree_contract == 0):?>
                    <a data-toggle="modal" data-target="#contractModal" href="#">
                        Партнерская программа
                    </a>
                <?else:?>
                    <li><a href="/profile">Партнерская программа</a></li>
                <?endif;?>
                </li>

            <?endif;?>
          <li><a href="/academy/course">Мое обучение</a></li>
		  <?if($is_agent && $agent_status == \backend\models\AgentForm::ACTIVE):?>
                <li><a href="/profile/transfer">Переводы</a></li>
          <?endif;?>
          <li><a href="/">Все курсы</a></li>
            <li>
                <div class="bab">
                    <div id="ytWidgetDeskMobile">

                    </div>
                </div>
            </li>

          <hr>
          <li><a href="/academy/profile">Настройки</a></li>

          <li><form action="/site/logout" method="post" >
                  <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
                  <button class="fon-transparent border-none text-left" type="submit">Выход</button>
              </form></li>
        <?else:?>
            <li><a href="/site/login" class="">Вход</a>
                </li>
            <li><a href="/site/signup" class="">Регистрация</a></li>
        <?endif;?>
        </ul>
      </div>
    </div>