<?php

use common\models\Parts;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Courses */
$parts = Parts::find()->where(['course_id'=>$model->id])->orderBy('position asc');

$lessons = new ActiveDataProvider([
    'query' => \common\models\Lessons::find()->where(['course_id'=>$model->id]),
]);

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Курсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$main_url = Yii::$app->params['mainUrl'];
$url = $main_url.'/'.$model->alias;

?>
<div class="courses-view">
    <p>
        Статус: <? if($model->is_active == 0) echo "Отключен"; else echo "Активный";?>
    </p>
    <p>
        Алиас: <?=$model->alias?>
    </p>
    <p>
        <a href="<?=$url?>" target="_blank">Открыть ссылку</a>
    </p>
    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Добавить экран', ['/course-screens/create?c_id='.$model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все экраны страницы курса "<?=$model->title?>"</h3>



        </div>
        <!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
                <tr>
                    <th>Позиция</th>
                    <th>Сдвинуть</th>
                    <th>Название экрана</th>
                    <th>Статус</th>
                    <th>Включить<br>/отключить</th>
                </tr>

    <?
        $screens = \common\models\CourseScreens::find()->where(['course_id'=>$model->id])->orderBy('position asc')->all();
        $sc_count = count($screens);
        $i = 0;
        foreach ($screens as $screen) {
            $i++;

            ?>

            <tr>
                <td><?=$screen['position']?></td>
                <td >
                    <? if($screen['position']>3 and $screen['position']<100){?>
                        <a style="margin-right: 20px" href="/courses/position-up?id=<?=$screen['id']?>"><i class="fa fa-arrow-up"></i></a>
                    <?
                        $style = '';
                    }else{
                        $style = 'style="margin-left: 37px"';
                    } ?>

                    <? if($screen['position']>2 and $screen['position']<100 and $i != $sc_count){?>
                        <a <?=$style?> href="/courses/position-down?id=<?=$screen['id']?>"><i class="fa fa-arrow-down"></i></a>
                    <?} ?>
                </td>
                <td><a href="/course-screens/view?id=<?=$screen['id']?>"><?=$screen['title']?></a></td>
                <td>
                    <? if($screen['is_active'] == 0){?>
                        <span class="label label-warning">Отключен</span>
                    <?}else{?>
                        <span class="label label-success">Активен</span>
                    <?}?>

                </td>
                <td class="text-center">
                <? if($screen['is_active'] == 0){?>
                    <a href="/courses/status?id=<?=$screen['id']?>"><i style="color: green" class="fa fa-power-off"></i></a></td>
                <?}else{?>
                    <a href="/courses/status?id=<?=$screen['id']?>"><i style="color: red" class="fa fa-power-off"></i></a></td>
                <?}?>

                </td>



            </tr>

        <?}
    ?>
                </tbody></table>
        </div>
        <!-- /.box-body -->
    </div>


</div>
<div class="parts-index">

    <h3><?= Html::encode('Модули') ?></h3>

    <p>
        <?= Html::a('Добавить модуль', ['/parts/create?c_id='.$model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все модули курса "<?=$model->title?>"</h3>



        </div>
        <!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
                <tr>
                    <th>Позиция</th>
                    <th>Сдвинуть</th>
                    <th>Название модуля</th>
                </tr>

                <?

                $sc_count = count($parts->all());
                $i = 0;
                foreach ($parts->all() as $part) {
                    $i++;

                    ?>

                    <tr>
                        <td><?=$part['position']?></td>
                        <td >
                            <? if($part['position']>1){?>
                                <a style="margin-right: 20px" href="/parts/position-up?id=<?=$part['id']?>"><i class="fa fa-arrow-up"></i></a>
                                <?
                                $style = '';
                            }else{
                                $style = 'style="margin-left: 37px"';
                            } ?>

                            <? if($i != $sc_count){?>
                                <a <?=$style?> href="/parts/position-down?id=<?=$part['id']?>"><i class="fa fa-arrow-down"></i></a>
                            <?} ?>
                        </td>
                        <td><a href="/parts/view?id=<?=$part['id']?>"><?=$part['title']?></a></td>

                    </tr>

                <?}
                ?>
                </tbody></table>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<div class="parts-index">

    <h3><?= Html::encode('Участники') ?></h3>
    <?//$user_courses = \common\models\UserCourses::find()->where(['course_id'=>$model->id])->all();
        $user_courses = [];
    ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все участники курса "<?=$model->title?>" - <?=count($user_courses)?></h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
                <tr>
                    <th>#</th>
                    <th>Пользователь</th>
                    <th>Дата добавления</th>
                </tr>

                <?

                $i = 0;
                foreach ($user_courses as $user_course) {
                    $user = \common\models\User::findOne($user_course['user_id']);
                    if(empty($user)) continue;
                    $i++;
                    ?>

                    <tr>
                        <td><?=$i?></td>
                        <td >
                            <a href="/users/view?id=<?=$user_course['user_id']?>"><?=$user['email']?></a>
                        </td>
                        <td><?=$user_course['date']?></td>

                    </tr>

                <?}
                ?>
                </tbody></table>
        </div>
        <!-- /.box-body -->
    </div>
</div>