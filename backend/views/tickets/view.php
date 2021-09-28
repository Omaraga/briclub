<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Tickets;

/* @var $this yii\web\View */
/* @var $model common\models\Tickets */

/**/
function getRole($userId, $ru = true){
    $user = User::findOne($userId);
    $all_access = \common\models\Access::find()->where(['developer'=>1])->all();
    $access = [];
    $sendTo = null;
    foreach ($all_access as $one_access) {
        $access[] = $one_access['username'];
    }
    if(in_array($user['username'], $access)){
        if ($ru){
            return 'Разработчик';
        }else{
            return 'Developer';
        }

    }else{
        if ($ru){
            return 'Техподдержка';
        }else{
            return 'Admin';
        }
    }
}
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Запросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$payment_comment = 'Данная услуга предоставляется на платной основе. Оплатите услугу для дальнейшего разрешения вашего запроса в службу технической поддержки.';
\yii\web\YiiAsset::register($this);
$messages = \common\models\Messages::find()->where(['ticket_id'=>$model->id, 'is_private'=>null])->all();
$privateMessages = \common\models\Messages::find()->where(['ticket_id'=>$model->id, 'is_private'=>1])->all();
$user = \common\models\User::findOne($model['user_id']);
$currUser = \common\models\User::findOne(Yii::$app->user->identity['id']);

$script = <<< JS
    function sendMessage(data){        
        $.ajax({
            url: '/tickets/update-message',
            data: data,
            type: 'POST',
            dataType:'json',
            success: function(response){
                console.log(response);
                if (response.status == 'success'){
                    let role = (response.userRole == 'admin')?'Техподдержка':'Разработчик';
                    let today = new Date();
                    let minute = (Math.floor(today.getMinutes()/10) > 0)?today.getMinutes():'0'+today.getMinutes();
                    let date = today.getDate()+'.'+today.getMonth()+'.'+today.getFullYear()+' '+today.getHours()+':'+minute;
                    let newMessageHtml = '<div class="message message-my"><div class="message-head">'+role+':</div>'+data.message+'<span class="time">'+date+'</span></div>';
                    $('.right-site-content .chat').append(newMessageHtml);
                    $('#task-status-control').fadeOut();
                    updateStatus(data.status);
                }
                
            }
        });
    }
    function updateStatus(status){
        let html = '';
        if (status === 'toWork'){
            html = '<p><i class="fa fa-spinner" aria-hidden="true"></i>  Принято в работу</p>';
            $('#task-status').html(html);
        }else if(status === 'toCheck'){
            html = '<p><i class="fa fa-list" aria-hidden="true"></i>  На проверке</p>';
            $('#task-status').html(html);
        }else if(status === 'toClose'){
            html = '<p><i class="fa fa-check-circle-o" aria-hidden="true"></i>  Завершен</p>';
            $('#task-status').html(html);
        }
        
    }
    $('.short-message').click(function (e){
        e.preventDefault();
        let shortData = $(this).data('message');
        let message = shortData.message;
        let status = shortData.status;
        let jsonData = $('.right-sidebar #comment').data('json');
        let userId = jsonData.userId;
        let ticketId = jsonData.ticketId;
        let token = '123456';
        let data = {from: userId, message: message, ticketId: ticketId, token: token, status: status};
        sendMessage(data);
    });
    $('#sendMessage').click(function (e){
        e.preventDefault();
        let message = $('.right-sidebar #comment').val();
        let jsonData = $('.right-sidebar #comment').data('json');
        $('.right-sidebar #comment').val('');
        let userId = jsonData.userId;
        let ticketId = jsonData.ticketId;
        let token = '123456';
        let data = {from: userId, message: message, ticketId: ticketId, token: token, status: null};
        sendMessage(data);
        
    });
    $("#right-side-btn").click(function (){
        $('.right-site-content').fadeToggle(300, function (){
            let margin = $('#right-side-btn').data('margin');
            if (parseInt(margin) === 0){
                $("#right-side-btn").css('margin-right', '30%');
                $('#right-side-btn').data('margin', 30);
            }else{
                $("#right-side-btn").css('margin-right', 0);
                $('#right-side-btn').data('margin', 0);
            }
            
            
        });
        
    });
    $(document).mouseup(function (e){ // событие клика по веб-документу
    var mobileMenu = $(".right-sidebar"); // тут указываем ID элемента
    if (!mobileMenu.is(e.target) // если клик был не по нашему блоку
        && mobileMenu.has(e.target).length === 0) { // и не по его дочерним элементам
        $('.right-site-content').hide(); // скрываем его
        $("#right-side-btn").css('margin-right', 0);
        $('#right-side-btn').data('margin', 0);
       
    }
    
    
    
});
JS;
$this->registerJs($script);
?>
<style>
    .right-site-content{
        position: fixed;
        right: 0px;
        top:10%;
        display: none;
        width: 30%;
        height: 100vh;
        border-radius: 10px;
        background-color: #0a315c;
        z-index: 100;
        padding: 1rem 2rem 2rem 2rem;
        color: #fff;

    }
    .right-site-content .chat{
        overflow-y: scroll;
        height: 40vh;

    }
    .right-site-content #sendMessage {
        background-color: #FF931E;
        color: #fff;
        font-weight: bold;
        border: none;

    }
    .right-site-content textarea{
        border-radius: 10px;
        color: #000;
    }
    .right-site-content .message{
        width: 80%;
        position: relative;
        border-radius: 6px;
        margin-bottom: 5px;
        background-color: #00c0ef;
        padding: 5px 5px 15px 5px;

    }
    .right-site-content .message .message-head{
        width: 100%;
        color: #362b36;
        border-bottom: 1px solid #8ec5fd;
        font-size: 11px;
    }
    .right-site-content .message-my{
        margin-left: 19%;
    }
    .right-site-content .message-my .message-head{
        color: yellow;
    }
    .right-site-content .message-him{
        margin-left: 0;
    }

    .right-site-content .message .time{
        font-size: 10px;
        color: #0a315c;
        margin-top: 5px;
        position: absolute;
        right: 5px;
        bottom: 1px;
    }

    #right-side-btn {
        cursor: pointer;
        padding:0px 0px 0px 0px;
        text-align: center;
        margin:0px;
        margin-right: 0;
        width: 300px;
        height:40px;
        background:#FF931E;
        z-index:15;
        border-radius: 5px 5px 0px 0px;
        -moz-transform:rotate(-90deg);
        -ms-transform:rotate(-90deg);
        -o-transform:rotate(-90deg);
        -webkit-transform:rotate(-90deg);
        transform-origin: bottom right;
        position: fixed;
        right: 0px;
    }
    #right-side-btn p {
        color:#fff;
        display:inline-block;
        line-height:40px;
    }
    #newMessage{
        transition: opacity 1s;s
    }

</style>


<div class="right-sidebar">
    <div id="right-side-btn" data-margin="0">
        <i class="fa fa-commenting-o" aria-hidden="true" id="newMessage" style="color: #fff; font-size: 14px"></i>
        <p>Отправить разработчикам</p>
    </div>
    <div class="right-site-content">
        <div class="status" id="task-status">
            <?if($model->dev_status == 0):?>
                <p><i class="fa fa-spinner" aria-hidden="true"></i>  В обработке</p>
            <?elseif($model->dev_status == 1):?>
                <p><i class="fa fa-spinner" aria-hidden="true"></i>  Принято в работу</p>
            <?elseif($model->dev_status == 2):?>
                <p><i class="fa fa-list" aria-hidden="true"></i>  На проверке</p>
            <?elseif ($model->dev_status == 3):?>
                <p><i class="fa fa-check-circle-o" aria-hidden="true"></i>  Завершен</p>
            <?endif;?>
        </div>
        <div id="task-status-control">
            <?if(getRole($currUser['id'], false) == "Developer"):?>
                <?if($model->dev_status == 0):?>
                    <button class="btn btn-success short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>'toWork', 'message'=>'Заявка принято в работу, постараемся обработать в кратчайший срок.']));?>">Принято в работу</button>
                    <button class="btn btn-danger short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>'toCheck', 'message'=>'Ошибка со стороны пользователя. Для решения требуется выставить счет пользователю.']));?>">Ошибка пользователя</button>
                <?elseif($model->dev_status == 1):?>
                    <button class="btn btn-success short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>'toCheck', 'message'=>'Заявка обработана, просим проверить.']));?>">Сделано</button>
                    <button class="btn btn-danger short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>'toCheck', 'message'=>'Ошибка со стороны пользователя. Для решения требуется выставить счет пользователю.']));?>">Ошибка пользователя</button>
                <?endif;?>
            <?else:?>
                <?if ($model->dev_status == 0):?>
                    <button class="btn btn-success short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>null, 'message'=>'Просим посмотреть запрос. Номер заявки:'.$model->id.', Тема:'.$model->title]));?>">Отправить разработчикам</button>
                <?elseif($model->dev_status == 2):?>
                    <p>Принять работу?</p>
                    <button class="btn btn-success short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>'toClose', 'message'=>'Проблема решена, спасибо.']));?>">Принять</button>
                    <button class="btn btn-danger short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>'toWork', 'message'=>'Проблема не решена, просим обработать повторно.']));?>">Отказ</button>
                <?elseif($model->dev_status == 1):?>
                    <button class="btn btn-success short-message" data-message="<?=htmlspecialchars(json_encode(['status'=>null, 'message'=>'На какой стадии решения заявки?']));?>">Узнать статус</button>
                <?endif;?>
            <?endif;?>
        </div>
        <hr>
        <div class="chat">
            <?foreach ($privateMessages as $pr_message):?>
                <?if($currUser['id'] == $pr_message['user_id']):?>
                    <div class="message message-my" data-id="<?=$pr_message['id'];?>">
                        <div class="message-head"><?=getRole($pr_message['user_id']);?>:</div>
                        <?=$pr_message['text'];?>
                        <span class="time"><?=date('d.m.Y H:i', $pr_message['time']);?></span>
                    </div>
                <?else:?>
                    <div class="message message-him" data-id="<?=$pr_message['id'];?>">
                        <div class="message-head"><?=getRole($pr_message['user_id']);?>:</div>
                        <?=$pr_message['text'];?>
                        <span class="time"><?=date('d.m.Y H:i', $pr_message['time']);?></span>
                    </div>
                <?endif;?>
            <?endforeach;?>

        </div>
        <div class="form-group">
            <label for="comment">Сообщение:</label>
            <?$dataJson = ['userId'=>Yii::$app->user->identity['id'], 'ticketId'=>$model['id']];?>
            <textarea class="form-control" rows="5" id="comment" data-json="<?=htmlspecialchars(json_encode($dataJson), ENT_QUOTES, 'UTF-8');?>"></textarea>
            <button class="btn btn-default " style="margin-top: 10px" id="sendMessage">Отправить</button>
        </div>
    </div>
</div>
<div class="tickets-view">

    <p>
        <?= Html::a('Изменить статус', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?if($model->payment_status == 0):?>
            <?= Html::a('Выставить счет', ['/bills/create', 'sender_id' => $model->user_id,'comment'=>$payment_comment, 'ticket_id'=>$model->id], ['class' => 'btn btn-primary']) ?>
        <?endif;?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'category',

            'title',
            [
                'label'=>'Пользователь',
                'value'=>function($data){
                    $user = \common\models\User::findOne($data['user_id']);
                    return Html::a($user['username'],'/users/view?id='.$data['user_id']);
                },
                'format' => 'raw'
            ],
            [
                'label'=>'Дата',
                'value'=>function($data){
                    return date("d.m.Y H:i",$data['time']);
                },
            ],
            [
                'label'=>'Статус',
                'value'=>function($data){
                    if($data['status']==3){
                        return "В обработке";
                    }elseif($data['status']==2){
                        return "Отвечен";
                    }elseif($data['status']==1){
                        return "Закрыт";
                    }
                },
            ],
            [
                'label'=>'Статус оплаты',
                'value'=>function($data){
                    if($data['payment_status']==Tickets::PAYMENT_STATUS_PAYED){
                        return "Оплачен";
                    }elseif($data['payment_status']==Tickets::PAYMENT_STATUS_NEED_PAY){
                        return "Ожидание оплаты";
                    }elseif($data['payment_status']==Tickets::PAYMENT_STATUS_NOT){
                        return "Не требуется оплата";
                    }
                },
            ],
        ],
    ]) ?>

    <h3>Сообщения</h3>

        <?
        foreach ($messages as $message) {?>
            <div class="card mt-4 mb-5">
                <div class="card-header" <?if($message['user_id'] == 1){?>style="background-color: #d2ecff;" <?}?>>
                    <span><?if($message['user_id'] == 1){echo "Администратор";}else{echo $user['fio'];}?></span>
                    <span style="float: right;"><?=date('d.m.Y H:i',$message['time'])?></span>
                </div>
                <div class="card-body">
                    <p class="card-text"><?=$message['text']?></p>
                    <?if($message['is_payment'] == 1):?>
                        <?if ($model->payment_status == Tickets::PAYMENT_STATUS_PAYED):?>
                            <button class="btn btn-success">Оплачено</button>
                        <?elseif ($model->payment_status == Tickets::PAYMENT_STATUS_NEED_PAY):?>
                            <button class="btn btn-danger">Ожидание оплаты</button>
                        <?endif;?>
                    <?endif;?>
                    <?if(!empty($message['link'])){?>
                        <a target="_blank" href="https://shanyrakplus.com/<?=$message['link']?>" class="btn btn-link">Скачать файл</a>
                    <?}?>

                </div>
            </div>
        <?}?>
    <div class="card mt-6">
        <div class="card-header">
            Написать сообщение
        </div>
        <div class="card-body">
            <?php $form = \yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <?= $form->field($messageForm, 'text')->textarea(['rows'=>6]); ?>
            <?= $form->field($messageForm, 'file')->fileInput(['class'=>'form-control-file btn btn-link pl-0']);  ?>
            <input class="btn-success btn" type="submit" value="<?=Yii::t('users', 'SEND')?>">
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
</div>
