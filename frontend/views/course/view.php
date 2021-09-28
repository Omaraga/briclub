<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $lesson['title'];
$prev = \common\models\Lessons::findOne($prev_id);

if(!empty($prev)){
    $pos = $prev['position'];
}else{
    $pos = $lesson['position'];
}

$user_homeworks = \common\models\UserHomeworks::find()->where(['user_id'=>Yii::$app->user->id,'lesson_id'=>$lesson['id']])->all();
$all_lessons = \common\models\Lessons::find()->where(['part_id'=>$part['id']])->orderBy('position asc')->all();
$user_lessons_db = array();
$user_lessons_db2 = \common\models\UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'course_id'=>$course['id']])->all();
foreach ($user_lessons_db2 as $item) {
    $lesson_item = \common\models\Lessons::findOne($item['lesson_id']);
    if($lesson_item['part_id']==$part['id']){
        $user_lessons_db[] = $item;
    }
}
$cur_lessons = \common\models\Lessons::find()->where(['part_id'=>$part['id']])->andWhere(['>=','position',$pos])->orderBy('position asc')->limit(5)->all();
$lesson_docs = \common\models\LessonDocs::find()->where(['lesson_id'=>$lesson['id']])->all();
$user = Yii::$app->user->identity;

?>
<style>
    .cours-video-container {
        overflow: hidden;
        position: relative;
        width:100%;
    }

    .cours-video-container::after {
        padding-top: 56.25%;
        display: block;
        content: '';
    }

    .cours-video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .mb-5, .my-5 {
        margin-bottom: 3rem!important;
    }
    .mt-4, .my-4 {
        margin-top: 1.5rem!important;
    }
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    .card-header:first-child {
        border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
    }
    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1.25rem;
    }
</style>
<?
$flashes = Yii::$app->session->allFlashes;
if(!empty($flashes)){
    foreach ($flashes as $key => $flash) {?>
        <div class="alert alert-<?=$key?> alert-dismissible fade show" role="alert">
            <?=$flash?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?}}
?>
    <main class="learnPage">
        <div class="container">
            <div class="hgroup pb-0">
                <h1 class="h1"><?=$lesson['title']?></h1>
            </div>
            <div class="row">
                <div class="col-xl-10">
                    <!--                <h2 class="h3">Вводный урок</h2>-->
                    <div class="learn-info"><!--<span>Вы на <?/*=count($user_lessons_db)*/?> уроке из <?/*=count($all_lessons)*/?></span>--> <span>Модуль: <?=$part['title']?></span> <span>Курс: <?=$course['title']?></span></div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?=count($user_lessons_db)*100/count($all_lessons)?>%" aria-valuenow="<?=count($user_lessons_db)*100/count($all_lessons)?>" aria-valuemin="<?=count($user_lessons_db)*100/count($all_lessons)?>" aria-valuemax="100"><b><?=round(count($user_lessons_db)*100/count($all_lessons))?>%</b></div>
                    </div>

                    <div class="lessons">
                        <?
                        $p = 0;
                        foreach ($cur_lessons as $next_lesson) {
                            $p++;
                            //$acces = \common\models\UserLessons::
                            $access = true;
                            if($course['type'] == 5){
                                $access = false;
                                $this_lesson = \common\models\UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'lesson_id'=>$next_lesson['id']])->one();
                                if($this_lesson['status'] == 3){
                                    $access = true;
                                }
                            }
                            if($next_lesson['id'] == $lesson['id'] or $access){?>
                            <div class="item active">
                                <div class="item-block position-relative ">
                                    <div class="lesson-number">
                                        <a href="/course/view/<?=$next_lesson['id']?>" class="number stretched-link"><?=$next_lesson['position']?></a>
                                        <span class="text">урок</span>
                                    </div>
                                    <span class="lesson-name"><?=mb_substr($next_lesson['title'],0,18)?></span>
                                    <span class="lesson-module"><?=$part['title']?></span>
                                </div>
                                <?
                                    if($next_lesson['id'] == $lesson['id']){?>
                                        <div class="status-text">Текущий урок</div>
                                    <?}
                                ?>
                            </div>
                            <?}else{?>
                                <div class="item">
                                    <div class="item-block">
                                        <div class="lesson-number">
                                            <span class="number"><?=$next_lesson['position']?></span>
                                            <span class="text">урок</span>
                                        </div>
                                        <span class="lesson-name"><?=mb_substr($next_lesson['title'],0,18)?></span>
                                        <span class="lesson-module"><?=$part['title']?></span>
                                    </div>
                                    <?
                                    if($this_lesson['status'] == 2){
                                        $seconds = $this_lesson['start'] - time();
                                        $minutes = floor($seconds / 60);
                                        $hours = floor($minutes / 60);
                                        $minutes = $minutes - ($hours * 60);
                                        ?>
                                        <div class="status-text"><?=$hours?> ч. <?=$minutes?> м.</div>
                                    <?}
                                    ?>
                                </div>
                            <?}
                            ?>
                        <?}
                        ?>
                    </div>

                    <div class="lessons-content">
                        <div class="video">
                            <?=$frame?>
                        </div>
                        <?if(!empty($lesson['description'])){?>
                            <h4 class="h4">Описание</h4>
                            <p><?=$lesson['description']?></p>
                        <?}?>
                        <?if(!empty($lesson['homework'])){?>
                            <h4 class="h4">Домашнее задание</h4>
                            <p><?=$lesson['homework']?></p>
                        <?}?>
                        <?if(!empty($lesson_docs)){?>
                            <h4 class="h4">Дополнительные материалы</h4>
                            <?
                            foreach ($lesson_docs as $lesson_doc) {
                                $link = $lesson_doc['link'];
                                if($lesson_doc['type'] == 3){
                                    $link = $lesson_doc['link2'];
                                }
                                $format = explode('.',$link);
                                $format = $format[count($format)-1];
                                ?>
                                <p><?=$lesson_doc['title']?>.<?=$format?><a target="_blank" class="btn btn-link" href="<?=$link?>">скачать</a></p>
                            <?}?>

                        <?}?>

                    </div>
                    <?if(!empty($user_homeworks)){?>
                        <h4 class="mt-4 pt-5">Ваши домашние задания</h4>
                    <?
                    foreach ($user_homeworks as $message) {?>
                        <div class="card mt-4 mb-5">
                            <div class="card-header" <?if($message['is_admin'] == 1){?>style="background-color: #d2ecff;" <?}?>>
                                <span><?if($message['is_admin'] == 1){echo "Преподаватель";}else{echo $user['username'];}?></span>
                                <span style="float: right;"><?=date('d.m.Y H:i',$message['time'])?></span>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?=$message['text']?></p>
                                <?if(!empty($message['link'])){?>
                                    <a href="<?=$message['link']?>" class="btn btn-link">Скачать файл</a>
                                <?}?>

                            </div>
                        </div>
                    <?}?>
                    <?}?>
                    <div class="card mt-6 ">
                        <div class="card-header">
                            Написать сообщение
                        </div>
                        <div class="card-body">
                            <?php $form = ActiveForm::begin(['options'=>['class'=>'form mb-5']]); ?>
                            <?= $form->field($model, 'text')->textarea(['rows' => 8,'placeholder'=>'Введите текст'])->label(false) ?>
                            <?= $form->field($model, 'user_id')->hiddenInput(['value' => $user['username']])->label(false) ?>
                            <div class="form-group">
                                <? echo $form->field($model, 'file')->fileInput(['class'=>'form-control-file btn btn-link pl-0']);  ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-lg mt-3']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </main>


                    <?/*
                    foreach ($user_homeworks as $user_homework) {*/?><!--
                        <div>
                            <p><?/*=$user_homework['text']*/?></p>
                            <?/*if(!empty($user_homework['link'])){*/?>
                                <p><a href="<?/*=$user_homework['link']*/?>">Скачать</a></p>
                            <?/*}*/?>
                            <p><?/*=date('d.m.Y H:i',$user_homework['time'])*/?></p>
                        </div>
                    --><?/*}
                    */?>


                    <?/*if(!empty($prev_id)){*/?><!--
                        <a type="button" href="/course/view/<?/*=$prev_id*/?>" class="btn btn-primary"><svg-icon class="control-any-video" name="previous" _nghost-fjy-c3=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22"><path fill="currentColor" fill-rule="evenodd" d="M16 14.49a1 1 0 01-1.603.798l-5.342-4.04a1 1 0 010-1.596l5.342-4.04A1 1 0 0116 6.41v8.08zM4 15.5a1 1 0 001 1h.667a1 1 0 001-1V5.4a1 1 0 00-1-1H5a1 1 0 00-1 1v10.1z" clip-rule="evenodd"></path></svg></svg-icon> Предыдушее видео</a>
                    <?/*}*/?>
                    <?/*if(!empty($next_id)){*/?>
                        <a type="button" href="/course/view/<?/*=$next_id*/?>"  class="btn btn-primary">Следующее видео <svg-icon class="next-video control-any-video" name="next" _nghost-fjy-c3=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="currentColor" fill-rule="evenodd" d="M4 6.901a1 1 0 011.567-.824L10.8 9.676a1 1 0 010 1.648l-5.234 3.599A1 1 0 014 14.099V6.901zM16 6a1 1 0 00-1-1h-.667a1 1 0 00-1 1v9a1 1 0 001 1H15a1 1 0 001-1V6z" clip-rule="evenodd"></path></svg></svg-icon></a>
                    <?/*}*/?>
                    <?/*if(!empty($prev_part_id) and empty($prev_id)){*/?>
                        <a type="button" href="/course/view/<?/*=$prev_part_id*/?>" class="btn btn-primary"><svg-icon class="control-any-video" name="previous" _nghost-fjy-c3=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22"><path fill="currentColor" fill-rule="evenodd" d="M16 14.49a1 1 0 01-1.603.798l-5.342-4.04a1 1 0 010-1.596l5.342-4.04A1 1 0 0116 6.41v8.08zM4 15.5a1 1 0 001 1h.667a1 1 0 001-1V5.4a1 1 0 00-1-1H5a1 1 0 00-1 1v10.1z" clip-rule="evenodd"></path></svg></svg-icon> Предыдуший модуль</a>
                    <?/*}*/?>
                    <?/*if(!empty($next_part_id) and empty($next_id)){*/?>
                        <a type="button" href="/course/view/<?/*=$next_part_id*/?>"  class="btn btn-primary">Следующий модуль <svg-icon class="next-video control-any-video" name="next" _nghost-fjy-c3=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="currentColor" fill-rule="evenodd" d="M4 6.901a1 1 0 011.567-.824L10.8 9.676a1 1 0 010 1.648l-5.234 3.599A1 1 0 014 14.099V6.901zM16 6a1 1 0 00-1-1h-.667a1 1 0 00-1 1v9a1 1 0 001 1H15a1 1 0 001-1V6z" clip-rule="evenodd"></path></svg></svg-icon></a>
                    --><?/*}*/?>
<?
echo \frontend\components\LoginWidget::widget();
?>