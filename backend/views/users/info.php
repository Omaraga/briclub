<?php

use common\models\Referals;
use common\models\User;
use common\models\Beds;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->fio;
$this->registerCssFile('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css');
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="user-view">
    <div class="col-6">
        <div class="row">
            <div class="col-12">
                <form id="w0" action="/users/info" method="get">
                    <div class="row">
                        <input type="hidden" name="id" value="<?=$model->id?>">
                        <div class="col-6">
                            <div class="form-group field-activities-username">
                                <label class="control-label" for="activities-username">Время регистрации от</label>
                                <input class="form-control" type="date" name="from" value="<?=$from?>">
                                <div class="help-block"><?=$error?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group field-activities-username">
                                <label class="control-label" for="activities-username">До</label>
                                <input class="form-control" type="date" name="to" value="<?=$to?>">
                                <div class="help-block"><?=$error?></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Поиск</button>    </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div>
    <h3><?="Контракты ". User::findOne($model->id)['username'];?> Shanyrakplus</h3>
    <table class="table table-striped table-bordered"><thead>
        <tr>
            <th>#</th>
            <th>
                <a href="/users/view?id=58&amp;sort=title" data-sort="title">Логин</a>
            </th>
            <th>
                <a href="/users/view?id=58&amp;sort=email" data-sort="email">Email</a>
            </th>
            <th>
                <a href="/users/view?id=58&amp;sort=tel" data-sort="tel">Дата активации</a>
            </th>
        </tr>
        </thead>
        <tbody>
        <?
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>$model->id])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['newmatrix'] ==1){

                if($user_mat['time_personal'] >=$time_start and $user_mat['time_personal'] <= $time_end){
                    $i++;
                    ?>
                    <tr data-key="36">
                        <td><?=$i?></td>
                        <td><a href="/users/view?id=<?=$user_mat['id']?>"><?=$user_mat['username']?></a></td>
                        <td>
                            <?=$user_mat['email']?>
                        </td>
                        <td>
                            <?=date('d.m.Y H:i',$user_mat['time_personal'])?>
                        </td>
                    </tr>
                <?}}}?>
        </tbody>
    </table>
    <h4><?echo "Всего ".$i." контрактов";?></h4>
    <br>
    <br>


</div>