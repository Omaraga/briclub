<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CourseScreens */
$c_id = $model->course_id;
/* @var $this yii\web\View */
/* @var $model common\models\CourseScreens */
$this->title = $model->title;
$course = \common\models\Courses::findOne($c_id);
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['/courses/index']];
$this->params['breadcrumbs'][] = ['label' => $course['title'], 'url' => ['/courses/view?id='.$course['id']]];
$this->params['breadcrumbs'][] = $this->title;
$screens = \common\models\Content::find()->where(['screen_course_id'=>$model->id])->all();
\yii\web\YiiAsset::register($this);
$group = \common\models\ContentGroups::find()->where(['screen_id'=>$model->screen_id])->one();
$content_types = \common\models\ContentTypes::find()->where(['group_id'=>$group['id']])->all();

/*$contents = array();
$k = 0;
foreach ($groups as $group) {
    $i = 0;
    foreach ($content_types as $content_type) {
        if($i>0){
            $contents[$k][$i]['title'] = $content_type['title'];
            $contents[$k][$i]['text'] = $group['title'];






        }else{
            $contents[$k][$i]['title'] = $content_type['title'];
            $contents[$k][$i]['text'] = $group['title'];
        }
        $i++;
    }
    $k++;
}*/

/*foreach ($screens as $screen) {
    foreach ($content_types as $content_type) {
        $k = $content_type['id'];
        if($screen['type'] == $content_type['id']){
            $groups[$i][$k]['title'] = $content_type['title'];
            $groups[$i][$k]['text'] = $screen['title'];


            $i++;
        }
        break;
    }
}*/


//$groups = \common\models\Content::find()->where(['screen_course_id'=>$model->id])->all();




/*echo "<pre>";
var_dump($contents);
echo "</pre>";
exit;*/
?>
<div class="course-screens-view">
    <?
    if($model->screen_id != 2 and $model->screen_id != 3){?>
        <p>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Уверены что хотите удалить экран?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?}
    ?>


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Контент экрана "<?=$model->title?>"</h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
                <tr>
                    <th>Тип</th>
                    <th>Содержание</th>
                    <th>Изменить</th>
                </tr>

                <?
                $i=0;
                foreach ($screens as $screen) {
                    $type = \common\models\ContentTypes::findOne($screen['type']);
                    if($type['group_id']==0 or empty($type['group_id'])){
                        $i++;
                        ?>

                        <tr>
                            <td><?=$type['title']; ?></td>
                            <td><?=$screen['title']?></td>

                            <td class="text-center">
                                <a href="/content/update?id=<?=$screen['id']?>&course_id=<?=$c_id?>"><span class="glyphicon glyphicon-pencil"></span></a>
                            </td>
                        </tr>

                    <?}}
                ?>
                </tbody></table>
        </div>
        <!-- /.box-body -->
    </div>
    <?
    $groups = \common\models\Groups::find()->where(['course_screen_id'=>$model->id])->all();
    if(!empty($groups)){?>

        <div class="box-header">
            <h3 class="box-title">Группы экрана "<?=$model->title?>"</h3>
        </div>
        <? $text = "Добавить ".$group['title']; ?>
        <p>
            <?= Html::a($text, ['/groups/create?screen_id='.$model->screen_id.'&c_id='.$model->id.'&group_id='.$group['id']], ['class' => 'btn btn-success']) ?>
        </p>

            <!-- /.box-header -->
            <?
            $l = 0;
            foreach ($groups as $group) {
                $l++;
                ?>
                <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?=$group['title']?> <?=$l?></h3>
                    <p style="float:right;">
                        <?= Html::a('Удалить', ['/groups/delete', 'id' => $group['id']], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Уверены что хотите удалить группу?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped table-bordered">
                        <tbody>
                        <tr>
                            <th>Тип</th>
                            <th>Содержание</th>
                            <th>Изменить</th>
                        </tr>

                        <?
                        $contents = \common\models\Content::find()->where(['group_id'=>$group['id']])->all();
                        foreach ($contents as $content) {
                            $type = \common\models\ContentTypes::findOne($content['type']);
                            if($type['group_id']>0){
                                ?>

                                <tr>
                                    <td><?=$type['title']; ?></td>
                                    <td><?=$content['title']?></td>

                                    <td class="text-center">
                                        <a href="/content/update?id=<?=$content['id']?>&course_id=<?=$c_id?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                    </td>
                                </tr>

                            <?}}
                        ?>
                        </tbody></table>
                </div>
                </div>
            <?}?>

            <!-- /.box-body -->


    <?}
    ?>


</div>
