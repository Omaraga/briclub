<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\ActionTypes;
use common\models\Canplatforms;
use common\models\Company;
use common\models\Confirms;
use common\models\Countries;
use common\models\Courses;
use common\models\Data;
use common\models\Matblocks;
use common\models\MatClons;
use common\models\MatParents;
use common\models\MatrixRef;
use common\models\MatrixStart;
use common\models\MLevels;
use common\models\MLevelsStart;
use common\models\Parents;
use common\models\Referals;
use common\models\User;
use common\models\User3;
use common\models\UserCourses;
use backend\models\PasswordResetRequestForm;
use common\models\UserEmailConfirmToken;
use common\models\UserLessons;
use common\models\UserPasswordResetToken;
use common\models\UserPlatforms;
use common\models\UserPlatforms2;
use common\models\Withdraws;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use Yii;
use common\models\Beds;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BedsController implements the CRUD actions for Beds model.
 */
class ChecksController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Beds models.
     * @return mixed
     */
    public function actionIndex($status_id = null)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Beds::find()->where(['type'=>1])->orderBy('id desc'),
        ]);
        if($status_id){
            $dataProvider = new ActiveDataProvider([
                'query' => Beds::find()->where(['type'=>1,'status'=>$status_id])->orderBy('id desc'),
            ]);
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'status_id' => $status_id,
        ]);
    }

    /**
     * Displays a single Beds model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionDelete($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionStatus()
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $get = Yii::$app->request->get();
        $model = $this->findModel($get['id']);
        $model->status = $get['status'];
        $model->save();
        return $this->redirect('/beds/view?id='.$model->id);
    }

    public function actionAccess($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = $this->findModel($id);
        if($model->access == 1){
            $model->access = null;
            $user_course = UserCourses::find()->where(['user_id'=>$model->user_id,'course_id'=>$model->course_id])->one();
            if(!empty($user_course)){
                $user_course->delete();
            }
        }else{
            $model->access = 1;
            $ex_user = User::findByEmail($model->email);
            if(!empty($model->user_id) or !empty($ex_user)){
                $user_id = $ex_user['id'];
                $user_course = UserCourses::find()->where(['user_id'=>$user_id,'course_id'=>$model->course_id])->one();
                if(empty($user_course)){
                    $user_course = new UserCourses();
                    $user_course->user_id = $user_id;
                    $user_course->course_id = $model->course_id;
                    $user_course->date = date("d.m.Y H:i");
                    $user_course->save();
                }
            }else{
                $transaction = Yii::$app->db->beginTransaction();
                if ($user = User::createUser($model->email,$model->title, $model->tel)) {
                    $reset = new PasswordResetRequestForm();
                    $reset->course_id = $model->course_id;
                    $reset->email = $model->email;

                    if ($reset->sendEmail()) {
                        Yii::$app->getSession()->setFlash('success', 'Пользователь создан');
                        $transaction->commit();
                    } else {
                        Yii::$app->getSession()->setFlash('error', Yii::t('users', 'CAN_NOT_SEND_EMAIL_FOR_CONFIRMATION'));
                        $transaction->rollBack();
                    };
                }else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('users', 'CAN_NOT_CREATE_NEW_USER'));
                    $transaction->rollBack();
                }
                $user_course = new UserCourses();
                $user_course->user_id = $user['id'];
                $user_course->course_id = $model->course_id;
                $user_course->date = date("d.m.Y H:i");
                $user_course->save();
                $model->user_id = $user['id'];
            }

        }
        $model->save();


        return $this->redirect('/beds/view?id='.$model->id);
    }
    public function actionNew()
    {

        $users = User::find()->where(['activ'=>1])->all();
        $i = 0;
        foreach ($users as $user) {
                $acts = Actions::find()->where(['user_id'=>$user['id']])->all();
                $sum = 0;
                foreach ($acts as $act) {
                    $type = ActionTypes::findOne($act['type']);
                    if($type['minus'] == 1){
                        $sum = $sum - $act['sum'];
                    }else{

                        $sum = $sum + $act['sum'];
                    }
                }
                if($sum == ($user['w_balans'] + $user['b_balans'])){

                }else{
                    $i++;
                    echo $user['id'].' '.$user['username']." - ".($user['w_balans'] + $user['b_balans'])." ---- ".$sum;
                    echo "<br>";
                }

        }
        echo $i;
        exit;

        //'1603291723'
        /*echo time();
        exit;*/
        /*$actions = Actions::find()->where(['type'=>105])->all();
        foreach ($actions as $action) {
            $user = User::findOne($action['user_id']);
            $action->user_id = $user['parent_id'];
            $action->save();
        }
        exit;*/

        /*$actions = Actions::find()->where(['like', 'title', '%У вас новый клон%', false])->all();

        foreach ($actions as $action) {
            $action->type = 11;
            $action->save();
        }
        exit;*/

        /*$actions = Actions::find()->where(['type'=>9])->all();
        foreach ($actions as $action) {
             $num = $action['title'][strlen($action['title'])-1];
            echo $action->comment = $num;
            $action->save();
            echo "<br>";
        }
        exit;*/

        /*$actions = Actions::find()->where(['type'=>9,'comment'=>6])->all();
        $i = 0;
        foreach ($actions as $action) {
            $mat = MatrixRef::find()->where(['user_id'=>$action['user_id'],'platform_id'=>$action['comment']-1,'slots'=>4])->all();
            if(empty($mat)){
                echo $action['user_id'];
                echo "<br>";
                $i++;
                //$ch_mat = MatrixRef::find()->where(['big_parent_id'=>$mat['id']])->orderBy('id desc')->one();

                $action->title = "Вы купили место на уровне ".$action['comment'];
                $action->type = 10;
                $action->sum = 32400;
                $action->save();

            }
        }
        echo $i;

        exit;*/


        /*$actions = Actions::find()->where(['type'=>9,'comment'=>6])->all();
        $i = 0;
        foreach ($actions as $action) {
            $mat = MatrixRef::find()->where(['user_id'=>$action['user_id'],'platform_id'=>$action['comment']-1,'slots'=>4])->one();
            if(!empty($mat)){

                $ch_mat = MatrixRef::find()->where(['big_parent_id'=>$mat['id']])->orderBy('id desc')->one();
                if($ch_mat['time']>$action['time']){
                    echo $action['user_id'];
                    echo "<br>";
                    $i++;
                    $action->title = "Вы купили место на уровне ".$action['comment'];
                    $action->type = 10;
                    $action->sum = 10800;
                    $action->save();
                }


            }
        }
        echo $i;

        exit;*/

        /*$mats = MatrixRef::find()->all();
        foreach ($mats as $mat) {
            $mat->id = $mat->id - 413;
            if(!empty($mat['parent_id'])){
                $mat->parent_id = $mat->parent_id - 413;
            }
            if(!empty($mat['big_parent_id'])){
                $mat->big_parent_id = $mat->big_parent_id - 413;
            }
            if(!empty($mat['shoulder1'])){
                $mat->shoulder1 = $mat->shoulder1 - 413;
            }
            if(!empty($mat['shoulder1_1'])){
                $mat->shoulder1_1 = $mat->shoulder1_1 - 413;
            }
            if(!empty($mat['shoulder1_2'])){
                $mat->shoulder1_2 = $mat->shoulder1_2 - 413;
            }
            if(!empty($mat['shoulder2'])){
                $mat->shoulder2 = $mat->shoulder2 - 413;
            }
            if(!empty($mat['shoulder2_1'])){
                $mat->shoulder2_1 = $mat->shoulder2_1 - 413;
            }
            if(!empty($mat['shoulder2_2'])){
                $mat->shoulder2_2 = $mat->shoulder2_2 - 413;
            }
            $mat->save();
        }

        $cans = Canplatforms::find()->all();
        foreach ($cans as $can) {
            $can->mat_id = $can->mat_id - 413;
            $can->save();
        }

        $clons = MatClons::find()->all();
        foreach ($clons as $clon) {
            $clon->mat_id = $clon->mat_id - 413;
            $clon->save();
        }*/

        /*$parents = MatParents::find()->all();
        foreach ($parents as $parent) {

            $parent->mat_id = $parent->mat_id - 413;
            $parent->parent_id = $parent->parent_id - 413;
            $parent->save();
        }*/


        /*$actions = Actions::find()->all();
        foreach ($actions as $action) {
            $action->delete();
        }

        $cans = Canplatforms::find()->all();
        foreach ($cans as $can) {
            $can->delete();
        }

        $bloks = Matblocks::find()->all();
        foreach ($bloks as $blok) {
            $blok->delete();
        }

        $mats = MatrixRef::find()->all();
        foreach ($mats as $mat) {
            $mat->delete();
        }

        $mat_clons = MatClons::find()->all();
        foreach ($mat_clons as $mat_clon) {
            $mat_clon->delete();
        }

        $mat_parents = MatParents::find()->all();
        foreach ($mat_parents as $mat_parent) {
            $mat_parent->delete();
        }

        $users = User::find()->all();
        foreach ($users as $user) {
            if($user['id'] == 1 or $user['id'] == 12750 or $user['id'] == 12751 or $user['id'] == 12752 ){
                $user->w_balans = 0;
                $user->b_balans = 0;
                $user->activ = 2;
                $user->newmatrix = 2;
                $user->save();
            }else{
                $user->delete();
            }
        }

        $refs = Referals::find()->all();
        foreach ($refs as $ref) {
            if($ref['user_id'] == 1 or $ref['user_id'] == 12750 or $ref['user_id'] == 12751 or $ref['user_id'] == 12752 ){

            }else{
                $ref->delete();
            }
        }

        exit;*/
        /*$starts = User::find()->where(['global'=>1])->orWhere(['newmatrix'=>1])->all();
        foreach ($starts as $start) {
            echo $start['email'];
            echo ",<br>";
        }*/

        /*$starts = User::find()->where(['personal'=>1])->orWhere(['newmatrix'=>1])->all();
        foreach ($starts as $start) {
            echo $start['email'];
            echo ",";
        }*/
        //exit;
        /*$users = User::find()->where(['global'=>1])->orWhere(['newmatrix'=>1])->all();
        foreach ($users as $user) {
            $courses = Courses::find()->where(['personal'=>1])->orWhere(['global'=>1])->all();
            foreach ($courses as $cours) {
                Courses::setAccess($user['id'],$cours['id']);
            }
        }
        exit;*/
        /*$refs = Referals::find()->where(['parent_id'=>9404])->all();
        foreach ($refs as $ref) {
            $referals = Referals::find()->where(['user_id'=>$ref['user_id']])->orderBy('level desc')->one();
            $referals->parent_id = 9826;
            $referals->save();
            echo "<br>";
        }
        exit;*/

        /*$users = User::find()->where(['global'=>1])->all();
        $i = 0;
        foreach ($users as $user) {
            $user_mat = UserPlatforms::find()->where(['user_id'=>$user['id'],'deleted'=>2,'platform_id'=>1])->one();
            if(!empty($user_mat)){
                $children = User::find()->where(['global'=>1,'parent_id'=>$user['id']])->count();
                if($children>1){
                    $i++;
                    echo $user['username'];
                    echo "<br>";
                }

            }
        }
        echo $i;
        exit;*/

        /*$users = User::find()->all();
        foreach ($users as $user) {
            $user->username = str_replace(' ','_',$user->username);
            $user->save();
        }
        exit;*/
        /*$users = [9205,9196,8859,8944,8851,8432,9197,8850];
        foreach ($users as $user) {
            $user = User::findOne($user);
            $user->time_personal = 1593626490;
            $user->save();
            $refs = Referals::find()->where(['user_id'=>$user['id']])->all();
            foreach ($refs as $ref) {
                $ref->time_personal = 1593626490;
                $ref->save();
            }
        }
        exit;
        $refs = Referals::find()->where(['parent_id'=>9196])->all();
        $i = 0;
        foreach ($refs as $ref) {
            $user = User::findOne($ref['user_id']);
            if($user['newmatrix'] == 1){
                $i++;
                echo $user['username']." - Плечо: ".$ref['shoulder'];
                echo "<br>";
            }
        }
        echo $i;
        exit;*/


    /*$actions = Actions::find()->where(['type'=>97])->all();
    $all = 0;
    foreach ($actions as $action) {
        if($action['user_id'] == 12273 or $action['user_id'] == 12381){
            continue;
        }
        $user = User::findOne($action['user_id']);
        echo $user['username'];
        echo " ".$action['sum'];
        echo " ".date('d.m.Y H:i',$action['time']);
        if($user['w_balans']>=$action['sum']){
            echo " - ".$user['w_balans'];
        }else{
            echo " - ".$user['w_balans'];
            echo " ---------------- ";
        }
        $all = $all + $action['sum'];
        echo "<br>";
    }
    echo $all;
    exit;*/
        /*$users = User::find()->where(['activ'=>1])->all();
        foreach ($users as $user) {
            if($user['start'] == 1){
                echo $user['email'];
                echo "<br>";
            }
        }

        exit;*/
        /*$com = Company::findOne(1);
        if($com->balans_pl0 == 0){
            $acts = Actions::find()->where(['>','time',1597677847])->all();
            foreach ($acts as $act) {
                $user = User::findOne($act['user_id']);
                if($act['type'] == 95){
                    $user->w_balans = $user->w_balans - $act['sum'];
                    $user->save();
                }
                if($act['type'] == 96){
                    $user->w_balans = $user->w_balans + $act['sum'];
                    $user->save();
                }
                $act->delete();
            }
        }
        $com->balans_pl0 = 1;
        $com->save();

        exit;*/

        /*$com = Company::findOne(1);
        if($com->balans_pl0 == 0){
            $users = User::find()->where(['start'=>1])->all();
            foreach ($users as $user) {
                $refs = Referals::find()->where(['user_id'=>$user['id']])->all();
                foreach ($refs as $ref) {
                    $ref->time_start = $user['time_start'];
                    $ref->save();
                }
            }
        }
        $com->balans_pl0 = 1;
        $com->save();

        exit;*/

        /*$users = User::find()->where(['global'=>1,'time_global'=>null])->all();
        foreach ($users as $user) {
            $user->time_global = $user['created_at'];
            $user->save();
        }
        echo count($users);
        exit;*/

        /*$com = Company::findOne(1);
        if($com->balans_pl0 == 0){
            $users = User::find()->where(['>','overdraft',0])->all();
            $i = 0;
            foreach ($users as $user) {
                echo $user['username']." - ".$user['overdraft'];
                echo "<br>";
            }
            echo $i;
        }
        $com->balans_pl0 = 1;
        $com->save();


        exit;*/

        /*echo strtotime('6.08.2020');
        echo "<br>";
        echo date('d.m.Y H:i',1596650400);
        exit;*/
        /*$users = Referals::find()->where(['parent_id'=>8850])->all();
        echo "Контракты ". User::findOne(8850)['username'];
        echo "<br>";
        echo "Start: ";
        echo "<br>";
        echo "<br>";
        foreach ($users as $user) {
            if($user['time_start'] >=1596650400){
                $i++;
                echo User::findOne($user['user_id'])['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";
        echo "Global: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>8850])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_global'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";

        echo "Personal: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>8850])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_personal'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";





        $i = 0;
        $users = Referals::find()->where(['parent_id'=>7572])->all();
        echo "Контракты ". User::findOne(7572)['username'];
        echo "<br>";
        echo "Start: ";
        echo "<br>";
        echo "<br>";
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_start'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";
        echo "Global: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>7572])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_global'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";

        echo "Personal: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>7572])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_personal'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";


        $i = 0;
        $users = Referals::find()->where(['parent_id'=>9404])->all();
        echo "Контракты ". User::findOne(9404)['username'];
        echo "<br>";
        echo "Start: ";
        echo "<br>";
        echo "<br>";
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_start'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";
        echo "Global: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>9404])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_global'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";

        echo "Personal: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>9404])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_personal'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";


        $i = 0;
        $users = Referals::find()->where(['parent_id'=>8807])->all();
        echo "Контракты ". User::findOne(8807)['username'];
        echo "<br>";
        echo "Start: ";
        echo "<br>";
        echo "<br>";
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_start'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";
        echo "Global: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>8807])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_global'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";

        echo "Personal: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>8807])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_personal'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";

        $i = 0;
        $users = Referals::find()->where(['parent_id'=>85])->all();
        echo "Контракты ". User::findOne(85)['username'];
        echo "<br>";
        echo "Start: ";
        echo "<br>";
        echo "<br>";
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_start'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";
        echo "Global: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>85])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_global'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";

        echo "Personal: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>85])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_personal'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";


        $i = 0;
        $users = Referals::find()->where(['parent_id'=>9332])->all();
        echo "Контракты ". User::findOne(9332)['username'];
        echo "<br>";
        echo "Start: ";
        echo "<br>";
        echo "<br>";
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_start'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";
        echo "Global: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>9332])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_global'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";

        echo "Personal: ";
        echo "<br>";
        echo "<br>";
        $i = 0;
        $users = Referals::find()->where(['parent_id'=>9332])->all();
        foreach ($users as $user) {
            $user_mat = User::findOne($user['user_id']);
            if($user_mat['time_personal'] >=1596650400){
                $i++;
                echo $user_mat['username'];
                echo "<br>";
            }
        }
        echo "<br>";
        echo "Всего ".$i." контрактов";
        echo "<br>";
        echo "<br>";*/

        /*$mats = MatrixStart::find()->where(['slots'=>4,'platform_id'=>4])->all();
        echo count($mats);
        echo "<br>";
        foreach ($mats as $mat) {
            $user_mat = User::findOne($mat['user_id']);
            if($user_mat['time_global'] == null){
                echo date('d.m.Y H:i',$user_mat['created_at']);
                echo "<br>";
            }
        }
        exit;*/

        /*MatrixStart::plusToRefMatrix(12106,3,false,2);

        exit;*/

        /*$users = User::find()->where(['global'=>1])->all();
        foreach ($users as $user) {
            $mat = UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
            if(empty($mat)){
                echo $user['username']." ".$user['id'];
                echo "<br>";
            }
        }
        exit;*/

        /*$acts = Actions::find()->where(['comment'=>"bon"])->all();
        $sum = 0;
        foreach ($acts as $act) {
            $sum = $act['sum'] + $sum;
        }
        echo $sum;
        exit;*/

        /*$mats = UserPlatforms::find()->where(['vacant'=>2,'children'=>2,'slots'=>2])->andWhere(['>','platform_id',1])->all();

        foreach ($mats as $mat) {
            $user = User::findOne($mat['user_id']);
            if(!empty($user)){
                echo $user['username']." ".$user['id'];
                echo "<br>";
                $user->w_balans = $user->w_balans + MLevels::findOne($mat['platform_id'])['line1']*2;
                $user->save();
                $action_bon = new Actions();
                $action_bon->time = time();
                $action_bon->status = 1;
                $action_bon->sum = MLevels::findOne($mat['platform_id'])['line1'];
                $action_bon->user_id = $user['id'];
                //$action_bon->user2_id = $mat['shoulder1'];
                $action_bon->title = "Начислены дробные бонусы за место на площадке ".$mat['platform_id'];
                $action_bon->type = 88;
                $action_bon->comment = "bon";
                $action_bon->save();
                $action_bon = new Actions();
                $action_bon->time = time();
                $action_bon->status = 1;
                $action_bon->sum = MLevels::findOne($mat['platform_id'])['line1'];
                $action_bon->user_id = $user['id'];
                //$action_bon->user2_id = $mat['shoulder1'];
                $action_bon->title = "Начислены дробные бонусы за место на площадке ".$mat['platform_id'];
                $action_bon->type = 88;
                $action_bon->comment = "bon";
                $action_bon->save();
            }
        }
        exit;*/


        /*$users = User::find()->where(['global'=>1])->all();
        foreach ($users as $user) {
            $mat = UserPlatforms::find()->where(['user_id'=>$user['id']])->all();
            if(empty($mat)){
                echo $user['username'];
                echo "<br>";
            }
        }
        exit;*/

        /*$mats = UserPlatforms::find()->where(['platform_id'=>1])->orderBy('id asc')->all();
        echo count($mats);
        $i = 813;
        foreach ($mats as $mat) {
            $user =  User::findOne($mat['user_id']);
            $user->order = $i;
            $user->save();
            echo "<br>";
            $i++;
        }
        exit;*/
        /*$users = User::find()->where(['global'=>1])->all();
        $i = 0;
        foreach ($users as $user) {

                $mat = UserPlatforms::find()->where(['user_id'=>$user['id']])->orderBy('id asc')->all();

                if(count($mat)>1){
                    echo $user['id'];
                }

        }
        //echo $i;

        exit;*/


        /*$users = User::find()->where(['password_hash'=>null])->all();


        echo "<pre>";

        foreach ($users as $user) {
            $user->setPassword("asdasdasdasdasdasdasd");
            $user->generateAuthKey();
            $user->save();
        }
        exit;*/


        /*$users = User::find()->where(['vacant'=>3])->all();
        foreach ($users as $user) {
            $mat = UserPlatforms2::find()->where(['user_id'=>$user['id']])->one();
            if(empty($mat)){
                echo $user['username'];
                echo "<br>";
            }else{
                $mat->vacant = 1;
                $mat->save();
            }
        }
        exit;*/

        /*$users = User::find()->where(['vacant'=>1])->all();
        foreach ($users as $user) {
            //1 чистим таблицу рефералов
            $ref = Referals::find()->where(['user_id'=>$user['id']])->all(); // сперва пользователи
            if(!empty($ref)){
                foreach ($ref as $item) {
                    $item->delete();
                }
            }

            $ref = Referals::find()->where(['parent_id'=>$user['id']])->all(); //потом спонсоры
            if(!empty($ref)){
                foreach ($ref as $item) {
                    $item->delete();
                }
            }

            // 2 чистим таблицу актовности
            $actions = Actions::find()->where(['user_id'=>$user['id']])->all();
            if(!empty($actions)){
                foreach ($actions as $action) {
                    $action->delete();
                }
            }

            // 3 чистим таблицу пополнения
            $actions = Withdraws::find()->where(['user_id'=>$user['id']])->all();
            if(!empty($actions)){
                foreach ($actions as $action) {
                    $action->delete();
                }
            }

            // 4 чистим таблицу курсов
            $actions = UserCourses::find()->where(['user_id'=>$user['id']])->all();
            if(!empty($actions)){
                foreach ($actions as $action) {
                    $action->delete();
                }
            }
            // 5 чистим таблицу уроков
            $actions = UserLessons::find()->where(['user_id'=>$user['id']])->all();
            if(!empty($actions)){
                foreach ($actions as $action) {
                    $action->delete();
                }
            }

            // 6 чистим таблицу хешей паролей
            $actions = UserPasswordResetToken::find()->where(['user_id'=>$user['id']])->all();
            if(!empty($actions)){
                foreach ($actions as $action) {
                    $action->delete();
                }
            }

            // 6 чистим таблицу хешей emailов
            $actions = UserEmailConfirmToken::find()->where(['user_id'=>$user['id']])->all();
            if(!empty($actions)){
                foreach ($actions as $action) {
                    $action->delete();
                }
            }
            $user->delete();
        }
        exit;*/

        /*$users = User::find()->where(['global'=>1,'vacant'=>2])->all();
        $i = 0;
        foreach ($users as $user) {
            $user_mat = UserPlatforms2::find()->where(['user_id'=>$user['id']])->all();
            if(empty($user_mat)){
                $user_mat_new = new UserPlatforms2();
                $user_mat_new->user_id = $user['id'];
                $user_mat_new->platform_id = $user['platform_id'];
                $user_mat_new->deleted = 2;
                $user_mat_new->save();
                echo $user['id']." ";
                echo $user['username']." ";
                echo $user['platform_id'];
                echo "<br>";
            }
        }
        echo $i;
        exit;*/


        /*Referals::setParents(9459,'global');
        exit;*/

        /*echo date('d.m.Y H:i',1593626390);
        exit;*/
        /*$refs = Referals::find()->where(['parent_id'=>8850])->andWhere(['>','time_personal',1593626389])->all();

        foreach ($refs as $ref) {
            $ref_user = User::findOne($ref['user_id']);
            $ref_user->overdraft = null;
            $ref_user->save();
            $ref_refs = Referals::find()->where(['user_id'=>$ref['user_id']])->all();
            foreach ($ref_refs as $ref_ref) {
                $ref_ref->time_personal = 1593626410;
                $ref_ref->save();
            }
        }
        exit;*/
        /*$users = User::find()->where(['<','created_at',1593626400])->andWhere(['newmatrix'=>1])->all();
        foreach ($users as $user) {
            $refs = Referals::find()->where(['user_id'=>$user['id']])->all();
            foreach ($refs as $ref) {
                $ref->time_personal = 1593626390;
                $ref->save();
            }
            $user->time_personal = 1593626390;
            $user->save();
        }
        exit;*/


        /*$overs = User::find()->where(['>','overdraft',0])->all();
        foreach ($overs as $over) {
            if ($over['activ'] !=1){
                $over->overdraft = null;
                $over->save();
            }
        }

        exit;*/
        /*$list = "mani888 --1539,mani444 --375,success88 --525,Zhadira --1420,Razi65 --525,azi73 --25,arai92 --25,Rozalia62 --99,millioner999 --875,DDD891 --925,universe --925,Gold999 --1325,Uspeh86 --1375,vershina898 --325,naznur999 --325,biznesmen --425,Bereke64 --425,Tabys888 --325,dkvera --375,almaz777 --325,tiko888 --525,dinar999 --525,alga999 --420,dostatok --600,trillioner --375,raketa --355,baljan888 --425,evro9999 --375,zhan777 --425,dos87 --525,aiza93 --100,simba6 --425,raihan2 --25,zoloto999 --25,akmaral --25,zhasulan --25,alinur --25,alengold --25,osman --25,ayanovna111 --24,botagoz1 --25,strela --25,prezident --25,Bogachka272 --25,pobeda --25,world --425,gold9 --525,sairan --25,bakosya78 --25,gulzhahan --100,nur2019 --100,nur73 --25,asanxan54 --25,asylbek --100,Bereke61 --200,rauhan777 --25,lazzat74 --25,laila111 --25,Zadyra1234 --100,saule999 --100,sunar777 --100,aisha999 --25,zamura --25,Altai --25,Altai65 --100,zhanar555 --100,Zhan555 --25,Neba999 --25,Zaure64 --25,Asel88 --100,Zado76 --25,Dollar777 --25,Astana86 --24,Mariyam88 --25,Mardan13 --25,Damir12 --25,Mansur777 --25,Ali93 --100,Omarmarlan --525,evronaz --525,udacha777 --25,almaty --325,Raikhan25 --25,dollar72 --325,bakytkul888 --99,YERLAN --100,kuanyzh --100,muhan381a --25,gulsum --425,gold8555 --25,Gulnar84 --55,elmir --55,damira3 --55,Laziza82 --55,amazumi --55,nurahm --55,vostok13 --55,baganashyl9674 --55,tima20 --55,Zhadyra1971 --55,raketa08 --55,danok --55,danabek00 --55,live1804 --55,Imanali2017 --55,balhi61 --55,dinara87 --55,bibidana --55,zvezdaAM --55,asema1978 --75,izobilie99 --20,Samir888 --75,baidauletovna --75,luka789kz --75,Saken79 --75,billa --75,Taskira689 --75,taiota70 --75,abu64 --75";


        $list_ar = explode(',',$list);
        $list_all = array();
        foreach ($list_ar as $item) {
            $item_ar = explode(' --',$item);
            $list_all[$item_ar[0]] = $item_ar[1];
        }

        foreach ($list_all as $item => $val) {
            $user = User::find()->where(['username'=>$item])->one();
            if(!empty($user)){
                $user->overbinar = 2;
                $user->save();
            }
        }

        exit;*/
        /*if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $users = User::find()->all();
        foreach ($users as $user) {

            if($user['newmatrix'] == 1){
                Courses::setAccess($user['id'],33);
            }
        }
        exit;*/
        //Referals::setParents(11515,'start');
        //Referals::setParents(11514,'start');
        //Referals::setParents(11515,'start');

        /*$users = User::find()->where(['activ'=>1])->all();
        echo count($users);
        echo "<br>";

        foreach ($users as $user) {
            $refs = Referals::find()->where(['user_id'=>$user['id']])->all();
            foreach ($refs as $ref) {
                if($ref['activ'] == 2){
                    echo $user['id'];
                    echo " ".$user['username'];
                    echo "<br>";
                }
            }
        }

        exit;*/

        /*$users = User::find()->where(['>','parent_id',0])->andWhere(['vacant'=>2])->all();
        foreach ($users as $user) {
            $ref = Referals::find()->where(['user_id'=>$user['id'],'level'=>1])->all();
            if(count($ref)<1){
                $ref = new Referals();
                $ref->user_id = $user['id'];
                $ref->level = 1;
                $ref->parent_id = $user['parent_id'];
                if($user['activ'] == 1){
                    $ref->activ = 1;
                }
                $ref->save();

            }
        }
        exit;*/

        /*$users = User::find()->where(['newmatrix'=>1])->all();
        foreach ($users as $user) {
            $user_mat = MatrixRef::find()->where(['user_id'=>$user['id'],'reinvest'=>0,'platform_id'=>1])->orderBy('id asc')->one();
            if(!empty($user_mat)){
                if(!empty($user_mat['time'])){
                    $user->time_start = $user_mat['time'];
                    $user->save();
                }

            }
        }
        exit;*/

        /*$users = User::find()->where(['start'=>1])->all();
        foreach ($users as $user) {
            $user_mat = MatrixStart::find()->where(['user_id'=>$user['id'],'reinvest'=>0,'platform_id'=>1])->orderBy('id asc')->one();
            if(!empty($user_mat)){
                if(!empty($user_mat['time'])){
                    $refs = Referals::find()->where(['user_id'=>$user['id']])->all();
                    foreach ($refs as $ref) {
                        if(empty($ref->time_start)){
                            $ref->time_start = $user_mat['time'];
                            $ref->activ = 1;
                            $ref->save();
                        }

                    }
                }

            }
            $refs = Referals::find()->where(['user_id'=>$user['id']])->all();
            foreach ($refs as $ref) {
                if(empty($ref->time)){
                    $ref->time = $user['created_at'];
                    $ref->activ = 2;
                    $ref->save();
                }

            }
        }
        exit;*/

        /*$refs = Referals::find()->where(['parent_id'=>11329])->orderBy('level asc')->all();

        $level = MatrixRef::find()->where(['user_id'=>11329])->count();
        foreach ($refs as $ref) {
            $user = User::findOne($ref['user_id']);
            $mat = MatrixRef::find()->where(['user_id'=>$user['id']])->all();
            if(!empty($mat)){
                $level = $level + count($mat);
                echo $user['id'];
                echo " Логин: ";
                echo $user['username'];
                echo " Уровень ";
                echo $ref['level'];
                echo "<br>";
            }
        }
        echo $level;
        exit;*/
        /*$refs = Referals::find()->where(['parent_id'=>11329])->orderBy('level asc')->all();

        $user_main = User::findOne(11329);
        $user_main->activ = 0;
        $user_main->start = 2;
        $user_main->global = 2;
        $user_main->newmatrix = 2;
        $user_main->username = "pers-".$user_main->username;
        $user_main->email = "pers-".$user_main->email;
        $user_main->save();
        foreach ($refs as $ref) {
            $user = User::findOne($ref['user_id']);
            $user->activ = 0;
            $user->start = 2;
            $user->global = 2;
            $user->newmatrix = 2;
            $user->username = "pers-".$user->username;
            $user->email = "pers-".$user->email;
            $user->save();
        }


        $array = [11330=>8851,11343=>8859,11331=>11461,11332=>11460,11344=>9196,11359=>8944,11360=>11429,11358=>9205,11345=>8432,11338=>11462,11329=>8850];

        foreach ($array as $key => $val) {
            $user_new = User::findOne($val);
            $mat = MatrixRef::find()->where(['user_id'=>$key])->all();
            foreach ($mat as $item) {
                $item->user_id = $val;
                $item->save();
            }
            $user_new->activ = 1;
            $user_new->newmatrix = 1;
            $user_new->save();
        }
        exit;*/
        /*echo date('d.m.Y H:i',1594964546);
        echo "baha";
        echo "<br>";
        echo date('d.m.Y H:i',1594585952);
        echo "samgau";
        exit;*/
        /*$referals = Referals::find()->where(['parent_id'=>9196])->orderBy('level asc')->all();
        foreach ($referals as $referal) {
            echo $referal['user_id'];
            echo "<br>";
        }
        exit;*/
        /*$user = User::findOne(8850);
        echo $user['username'];
        MatrixRef::plusToRefMatrix(8850,1,true,0);
        exit;*/

        /*$users = User::find()->where(['activ'=>null])->all();
        echo count($users);
        echo "<br>";
        foreach ($users as $user) {
            $refs = Referals::find()->where(['user_id'=>$user['id']])->all();
            foreach ($refs as $ref) {
                $ref->activ = 2;
                $ref->save();
            }
        }
        exit;*/
        
        /*$users = User::find()->where(['<','w_balans',0])->all();
        foreach ($users as $user) {
            $user->minusbalans = $user['w_balans'];
            $user->w_balans = 0;
            $user->save();
            echo $user['username'];
            echo " ".$user['w_balans'];
            echo "<br>";
        }
        exit;*/
        
        /*$mats = MatrixStart::find()->select(['user_id'])->distinct()->all();
        foreach ($mats as $mat) {
            $user = User::findOne($mat['user_id']);
            $user->w_balans = $user->w_balans - 15;
            $user->start = 2;
            $user->save();
        }
        exit;*/

       /* $users = User::find()->where(['activ'=>1,'global'=>1])->orderBy('order asc')->all();
        $i = 0;
        echo count($users);
        foreach ($users as $user) {
            if($user['global'] == 1){

                    echo $user['username'];
                    echo " - ";
                    echo $user['order'];
                    echo "<br>";
                    $i++;

            }
            //if($i == 50) break;
        }
        exit;*/

        /*$users = User::find()->all();
        foreach ($users as $user) {
            $ref = Referals::find()->where(['user_id'=>$user['id'],'level'=>1])->count();
            if($ref>1){
                echo $user['username'];
                echo " - ";
                echo $user['id'];
                echo "<br>";
            }
        }
        exit;*/

        /*$users = User::find()->where(['newmatrix'=>2])->andWhere(['>','platform_id',0])->all();
        foreach ($users as $user) {
            $user_mat = UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
            if(empty($user_mat)){
                echo $user['username'];
                echo "<br>";
            }
        }

        exit;*/

        /*$users = User::find()->where(['newmatrix'=>1])->all();
        foreach ($users as $user) {
            for ($i=1;$i<40;$i++){
                $refs = Referals::find()->where(['user_id'=>$user['id'],'level'=>$i])->all();
                if(count($refs)>1){
                    echo $user['id'];
                    echo "<br>";
                }
            }
        }
        exit;*/


        /*$users = User::find()->where(['<','minus_balans',0])->all();
        foreach ($users as $user) {
            echo $user['username'];
            echo " ";
            echo $user['minus_balans'];
            echo "<br>";
        }
        exit;*/

        /*$mat_list = MatrixRef::find()->where(['<','children',2])->andWhere(['>','slots',0])->all();
        echo count($mat_list);
        $i = 0;
        echo "<br>";
            foreach ($mat_list as $item) {
                $user = User::findOne($item['user_id']);

                echo $item['user_id']." - ";
                if($item['reinvest'] == 1){
                    if($item['slots'] == 1){
                        $user->minus_balans = $user->minus_balans + 50;
                        echo 50;
                    }elseif ($item['slots'] == 2){
                        $user->minus_balans = $user->minus_balans + 100;
                        echo 100;
                    }
                }else{
                    if($item['slots'] == 1){
                        echo 0;
                    }elseif ($item['slots'] == 2){
                        if($item['platform_id'] == 1){
                            $user->minus_balans = $user->minus_balans + 75;
                            echo 75;
                        }elseif($item['platform_id'] == 2){
                            $user->minus_balans = $user->minus_balans + 100;
                            echo 100;
                        }

                    }
                }
                $user->save();
                echo "<br>";
                $i++;
            }
        echo $i;
        exit;*/

        /*$mat_list = MatrixRef::find()->where(['<','children',2])->andWhere(['slots'=>2])->all();
        echo count($mat_list);
        echo "<br>";
        foreach ($mat_list as $item) {
            echo $item['user_id'];
            echo "<br>";
        }

        exit;*/
        /*$refs = Referals::find()->where(['parent_id'=>7637])->all();
        foreach ($refs as $ref) {
            $ref_user = User::findOne($ref['user_id']);
            if($ref_user['w_balans']>0){
                echo $ref_user['username'];
                echo " - ";
                echo $ref_user['w_balans'];
                echo "<br>";
            }

        }
        exit;*/

        /*$users = User::find()->where(['newmatrix'=>2])->andWhere(['<','w_balans',0])->all();
        foreach ($users as $user) {
            $user->w_balans = 0;
            $user->save();
        }
        exit;*/


        /*$users = User::find()->where(['>','id',11364])->andWhere(['newmatrix'=>1])->all();
        foreach ($users as $user) {
            if(!empty($user['parent_id'])){

                $referal = new Referals();
                $referal->user_id = $user['id'];
                $referal->parent_id = $user['parent_id'];
                $referal->level = 1;
                $referal->time = $user['created_at'];
                $parent_children = Referals::find()->where(['parent_id'=>$user['parent_id'],'level'=>1])->count();
                if($parent_children % 2 == 0){
                    $referal->shoulder = 1;
                }else{
                    $referal->shoulder = 2;
                }
                $referal->save();
                $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                foreach ($parent_referals as $parent_referal) {
                    $referal = new Referals();
                    $referal->user_id = $user['id'];
                    $referal->parent_id = $parent_referal['parent_id'];
                    $referal->level = $parent_referal['level'] +1;
                    $referal->shoulder = $parent_referal['shoulder'];
                    $referal->time = $user['created_at'];
                    $referal->save();
                }

            }
        }
        exit;*/

        /*$refs = Referals::find()->all();
        foreach ($refs as $ref) {
            $ref_user = User::findOne($ref['user_id']);
            if($ref_user['platform_id'] == null){
                echo "asd";
            }
        }
        exit;*/


        /*$users = User::find()->where(['>','id',11360])->limit(1000)->orderBy('id asc')->all();
        $c = 0;
        $k = 0;
        foreach ($users as $user) {
            $refs = Referals::find()->where(['parent_id'=>$user['id'],'level'=>1])->orderBy('user_id asc')->all();
            $i = 0;
            foreach ($refs as $ref) {
                if($ref['shoulder']>0){
                    $ref_refs = Referals::find()->where(['parent_id'=>$ref['user_id']])->all();
                    foreach ($ref_refs as $ref_ref) {
                        $ref_ref_ref = Referals::find()->where(['user_id'=>$ref_ref['user_id'],'parent_id'=>$user['id']])->one();
                        $ref_ref_ref->shoulder = $ref['shoulder'];
                        $ref_ref_ref->save();
                    }
                }
            }
            $c++;
            Yii::$app->session->set('user',$user['id']);
        }

        echo Yii::$app->session->get('user');*/
        //exit;

        /*$users = User::find()->orderBy('id asc')->all();
        $c = 0;
        $k = 0;
        foreach ($users as $user) {
            $refs = Referals::find()->where(['parent_id'=>$user['id'],'level'=>1])->orderBy('user_id asc')->all();
            $i = 0;
            foreach ($refs as $ref) {
                $ref_user = User::findOne($ref['user_id']);
                if($ref_user->platform_id >0){
                    $c++;
                    if($i%2 == 0){
                        $ref->shoulder = 1;
                    }else{
                        $ref->shoulder = 2;
                    }
                    //$ref->save();
                    $i++;
                }else{
                    $k++;
                }
            }
        }
        echo $c;
        echo "<br>";
        echo $k;
        exit;*/

        /*$users = User::find()->where(['like', 'username', 'vacant-' . '%', false])->orderBy('platform_id desc')->all();
        foreach ($users as $user) {
            $user->vacant = 1;
            $user->save();
        }
        exit;*/

        /*$users = User::find()->all();
        foreach ($users as $user) {
            if($user->newmatrix == 0){
                $user->newmatrix = 2;
                $user->save();
            }
        }
        exit;*/

		/*$users = User::find()->where(['<','w_balans',0])->all();
        foreach ($users as $user){
            echo $user['username'];
            echo " -";
            echo $user['w_balans'];
            echo "<br>";
        }

        exit;*/

        /*$refs = Referals::find()->where(['parent_id'=>[7568,83]])->orderBy('level asc')->all();
        $i = 0;
        foreach ($refs as $ref) {
            $withdraws = Withdraws::find()->where(['user_id'=>$ref['user_id']])->andWhere(['<','id',245])->all();
            if(!empty($withdraws)){
                foreach ($withdraws as $withdraw) {
                    if($withdraw['status'] == 3){
                        Withdraws::findOne($withdraw['id'])->delete();
                    }
                }

            }
        }

        exit;*/
		
        /*$withdraws = Withdraws::find()->where(['user_id'=>83])->andWhere(['<','id',245])->all();
        if(!empty($withdraws)){
            foreach ($withdraws as $withdraw) {
                if($withdraw['status'] == 1){
                    $user = User::findOne(83);
                    $user->w_balans = $user->w_balans - $withdraw['sum'];
                    $user->save();
                    $action = new Actions();
                    $action->sum = -$withdraw['sum'];
                    $action->user_id = $user['id'];
                    $action->title = "Перерасчет баланса за вывод";
                    $action->time = time();
                    $action->type = 111;
                    $action->status = 1;
                    $action->save();
                }
            }

        }*/
        /*$refs = Referals::find()->where(['parent_id'=>[7568,83]])->orderBy('level asc')->all();
        $i = 0;
        foreach ($refs as $ref) {
            $withdraws = Withdraws::find()->where(['user_id'=>$ref['user_id']])->andWhere(['<','id',245])->all();
            if(!empty($withdraws)){
                foreach ($withdraws as $withdraw) {
                    if($withdraw['status'] == 1){
                        $user = User::findOne($ref['user_id']);
                        $user->w_balans = $user->w_balans - $withdraw['sum'];
                        $user->save();
                        $action = new Actions();
                        $action->sum = -$withdraw['sum'];
                        $action->user_id = $user['id'];
                        $action->title = "Перерасчет баланса за вывод";
                        $action->time = time();
                        $action->type = 111;
                        $action->status = 1;
                        $action->save();
                    }
                }

            }
        }

        exit;*/

        /*$withdraws = Actions::find()->where(['user_id'=>7568,'type'=>3])->andWhere(['<','id',2004])->all();
        if(!empty($withdraws)){
            foreach ($withdraws as $withdraw) {

                $user = User::findOne(7568);
                $user2 = User::findOne($withdraw['user2_id']);
                $user->w_balans = $user->w_balans - $withdraw['sum'];
                $user->save();
                $action = new Actions();
                $action->sum = -$withdraw['sum'];
                $action->user_id = $user['id'];
                $action->title = "Перерасчет баланса за перевод пользователю ".$user2['username'];
                $action->time = time();
                $action->type = 111;
                $action->status = 1;
                $action->save();

            }

        }*/
        /*$refs = Referals::find()->where(['parent_id'=>[7568,83]])->orderBy('level asc')->all();
        $i = 0;
        foreach ($refs as $ref) {
            $withdraws = Actions::find()->where(['user_id'=>$ref['user_id'],'type'=>6])->andWhere(['<','id',2004])->all();
            if(!empty($withdraws)){
                foreach ($withdraws as $withdraw) {

                    $user = User::findOne($ref['user_id']);
                    $user2 = User::findOne($withdraw['user2_id']);
					echo $user['username'];
					echo " - ";
					echo $withdraw['sum'];
					echo "<br>";
                    $user->w_balans = $user->w_balans + $withdraw['sum'];
                    $user->save();      

                }

            }
        }
        echo $i;
        exit;*/
		
		/*$refs = Referals::find()->where(['parent_id'=>[7568,83]])->orderBy('level asc')->all();
        $i = 0;
        foreach ($refs as $ref) {
            $user = User::findOne($ref['user_id']);
                    if($user['id'] >10148){
						echo $user['username'];
			
					echo "<br>";
                    $user->w_balans = $user->w_balans - 75;
                    $user->save();
					}
					
        }
        echo $i;
        exit;
		*/
		
        /*$refs = Referals::find()->where(['parent_id'=>7568])->orderBy('level asc')->all();

        foreach ($refs as $ref) {
            $user = User::findOne($ref['user_id']);
            $new_user = User::find()->where(['balans'=>$user['id']])->one();
            if(empty($new_user)){
                $new_user = new User();
                $new_user->username = "vacant-".$user['username'];
                $new_user->email = "vacant-".$user['email'];
                $new_user->platform_id = $user['platform_id'];
                $new_user->order = $user['order'];
                $new_user->balans = $user['id'];
                $new_user->level = $user['level'];
                $new_user->setPassword('Gcfond2020com');
                $new_user->generateAuthKey();
                $new_user->save();
            }

        }

        exit;*/



        /*$refs = Referals::find()->where(['parent_id'=>7568])->orderBy('level asc')->all();

        foreach ($refs as $ref) {
            $user = User::findOne($ref['user_id']);
            $new_user = User::find()->where(['balans'=>$user['id']])->one();
            $new_user->parent_id = User::find()->where(['balans'=>$user['parent_id']])->one()['id'];
            $new_user->save();
        }

        exit;*/

        /*$refs = Referals::find()->where(['parent_id'=>7568])->orderBy('level asc')->all();

        foreach ($refs as $ref) {
            $mat = UserPlatforms::find()->where(['user_id'=>$ref['user_id']])->one();
            if(!empty($mat)){
                $new_user = User::find()->where(['balans'=>$ref['user_id']])->one();
                if(!empty($new_user)){
                    $mat->user_id = $new_user['id'];
                    $mat->save();
                }
            }
        }

        exit;*/



        /*$refs = Referals::find()->where(['parent_id'=>10148])->all();

        foreach ($refs as $ref) {
            $user = User::findOne($ref['user_id']);
            $children = User::find()->where(['parent_id'=>$user['id']])->all();
            $k=0;
            foreach ($children as $child) {
                if($child->platform_id>0){
                    $k++;
                }
            }
            if($k>2){
                echo $user['username'];
                echo " - ";
                echo $k;
                echo "<br>";
            }
        }
        exit;*/



        /*$parent_matrix = null;
        $user_parents = Referals::find()->where(['user_id'=>9439])->orderBy('level asc')->all();
        foreach ($user_parents as $user_parent) {
            $parent_matrix = MatrixRef::getPriorityChild($user_parent['user_id']);
            if(!empty($parent_matrix)){
                break;
            }
        }
        $parent_matrix = MatrixRef::getPriorityChild(9439,3);
        echo "<pre>";
        var_dump($parent_matrix);

        //echo $parent_children_levels;
        exit;*/


        /*$users = User::find()->where(['>','id',10234])->limit(200)->orderBy('id asc')->all();
        foreach ($users as $user) {
            Referals::setParents($user['id']);
        }

        exit;*/

        /*$countries = Countries::find()->all();
        foreach ($countries as $country) {
            if(!empty($country['link'])){
                $country->link = '/img/countries/'.$country['link'].'.gif';
                $country->save();
            }
        }

        exit;*/
        /*$user = User::findOne(10147);
        Parents::setChildren2($user['id'],$user['parent_id']);
        exit;*/
        //UserPlatforms::plusToSystem(9883);
        /*$users = User::find()->where(['level'=>4])->andWhere(['<','platform_id',3])->all();
        foreach ($users as $user) {
            echo $user['username'];
            echo "<br>";
       }
        exit;*/
        /*$children = User::find()->where(['>','level',3])->andWhere(['parent_id'=>9439])->all();
        echo "<pre>";
        var_dump($children);
        exit;*/
        /*$users = User::find()->where(['level'=>6])->all();

        foreach ($users as $user) {
            $children = User::find()->where(['parent_id'=>$user['id']])->andWhere(['>','level',5])->all();
            if(count($children)>1){
                $user = User::findOne($user['id']);
                $user['level'] = 7;
                $user->save();
            }
        }
        exit;*/
        /*$users = User::find()->all();

        foreach ($users as $user) {
            $user = User::findOne($user['id']);
            $user['level'] = 0;
            $user->save();
        }
        exit;*/

        /*$parents = Parents::find()->where(['level'=>44])->all();
        foreach ($parents as $parent) {
            $parent_user = Parents::find()->where(['user_id'=>$parent['parent_id'],'level'=>1])->one();
            if(!empty($parent_user)){
                $parent_w = new Parents();
                $parent_w->user_id = $parent['user_id'];
                $parent_w->parent_id = $parent_user['parent_id'];
                $parent_w->level = 45;
                $parent_w->save();
            }
        }*/

        /*$users = User::find()->all();
        foreach ($users as $user) {
            if(!empty($user['parent_id'])){
                $parent = new Parents();
                $parent->user_id = $user['id'];
                $parent->parent_id = $user['parent_id'];
                $parent->level = 1;
                $parent->save();
            }
        }*/


        /*$users = User::find()->orderBy('order asc')->all();
        $i = 0;
        foreach ($users as $user) {
            //if($user['platform_id']>2) continue;
            $first = null;
            $second = null;
            $third = null;
            $fourth = null;
            $fifth= null;
            $children = User::find()->where(['parent_id'=>$user['id']])->all();
            if(count($children)>1){
                $first = true;
                $f = 0;
                foreach ($children as $child) {
                    $children2 = User::find()->where(['parent_id'=>$child['id']])->all();
                    if(count($children2)>1){
                        $s = 0;
                        $f++;
                        foreach ($children2 as $item) {
                            $children3 = User::find()->where(['parent_id'=>$item['id']])->all();
                            if(count($children3)>1){
                                $f = 0;
                                $s++;
                                foreach ($children3 as $item) {
                                    $children4 = User::find()->where(['parent_id'=>$item['id']])->all();
                                    if(count($children4)>1){
                                        $f++;
                                        $d = 0;
                                        foreach ($children4 as $item) {
                                            $children5 = User::find()->where(['parent_id'=>$item['id']])->all();
                                            if(count($children5)>1){
                                                $d++;
                                            }
                                        }
                                        if($d>1){
                                            $fifth = true;
                                        }
                                    }
                                }
                                if($f>1){
                                    $fourth = true;
                                }

                            }
                        }
                        if($s>1){
                            $third = true;
                        }
                    }
                }
                if($f>1){
                    $second = true;
                }
            }
            if($first and $second and $third and $fourth and $fifth){
                $i++;
                echo $user['order'];
                echo " - ";
                echo $user['username'];
                echo " - ";
                echo $user['platform_id']-1;
                echo " - ";
                echo $user['w_balans'];
                echo "<br>";
            }
        }
        echo $i;
        exit;*/

        /*$users = User::find()->orderBy('order asc')->all();
        foreach ($users as $user) {
            echo $user['order'];
            echo " -- ";
            echo $user['username'];
            echo " -- ";
            echo $user['email'];
            echo " -- ";
            echo $user['fio'];
            echo " -- ";
            echo $user['phone'];
            echo "<br>";
        }
        exit;*/

        /*$users = User::find()->where(['order'=>null])->all();
        $i = 1984;
        foreach ($users as $user) {
            if($user['platform_id']<1) continue;
            $user = User::findOne($user['id']);
            $user->order = $i;
            $user->save();
            $i++;
        }*/
        /*$actions = Actions::find()->where(['type'=>1])->all();
        foreach ($actions as $action) {
            $action = Actions::findOne($action['id']);
            $p_id = User::findOne($action['user_id'])['platform_id'];
            if($p_id == 1){
                $action->title = "Вы в глобальном потоке";
            }else{
                $action->title = "Вы перешли на уровень ".($p_id-1);
            }
            $action->save();
        }*/


        /*$users = User::find()->all();
        $i = 0;
        foreach ($users as $user) {
            $user2 = User3::findOne($user['id']);
            if($user['w_balans']<$user2['w_balans']){
                $withdraws = Actions::find()->where(['user_id'=>$user['id']])->all();
                if(!empty($withdraws)){
                    echo $user['username'];
                    echo " $ ".$user['w_balans'];
                    echo " - ".$user2['w_balans'];
                    echo "<br>";
                    $i++;
                }

            }
        }
        echo $i;
        exit;*/
        /*$users = User::find()->where(['>','platform_id',0])->orderBy('order asc')->all();

        foreach ($users as $user) {
            $data = new Data();
            $data->user_id = $user['id'];
            $data->email = $user['email'];
            $data->email = $user['username'];
            $data->order = $user['order'];
            $data->save();
        }
        exit;*/

        /*$withdraws = Withdraws::find()->where(['status'=>1])->all();
        foreach ($withdraws as $withdraw) {
            $user = User::findOne($withdraw['user_id']);
            $children = User::find()->where(['parent_id'=>$user['id']])->all();
            if(count($children)<2){
                echo $user['username'];
                echo " - ";
                echo $withdraw['sum'];
                echo " - ";
                echo count($children);
                echo "<br>";
            }

        }
        exit;*/
        /*$users = User::find()->all();
        foreach ($users as $user) {
            $user = User::findOne($user['id']);
            $user->username = trim($user->username);
            $user->save();
        }*/
        /*$withdraws = Withdraws::find()->where(['<','id',60])->all();
        foreach ($withdraws as $withdraw) {
            $user = User::findOne($withdraw['user_id']);
            $user->w_balans = $user->w_balans - $withdraw['sum'];
            $user->save();
        }*/
        /*$users = User::find()->all();
        $i = 0;
        $balans = 0;
        foreach ($users as $user) {
            if($user['w_balans']>0){
                echo $user['username']." - ".$user['w_balans'];
                echo "<br>";
                $i++;
                $balans = $balans + $user['w_balans'];
            }
        }
        echo $i;
        echo $balans;
        exit;*/
        /*$user = User::findOne(7568);
        $children = User::find()->where(['parent_id'=>$user['id']])->all();
        foreach ($children as $child) {
            echo " - ".$child['username']." : ".$child['order'];
            echo "<br>";
            $children2 = User::find()->where(['parent_id'=>$child['id']])->all();
            foreach ($children2 as $child2) {
                echo " -  - ".$child2['username']." : ".$child2['order'];
                echo "<br>";
                $children3 = User::find()->where(['parent_id'=>$child2['id']])->all();
                foreach ($children3 as $child3) {
                    echo " -  -  - ".$child3['username']." : ".$child3['order'];
                    echo "<br>";
                    $children4 = User::find()->where(['parent_id'=>$child3['id']])->all();
                    foreach ($children4 as $child4) {
                        echo " -  -  -  - ".$child4['username']." : ".$child4['order'];
                        echo "<br>";
                        $children5 = User::find()->where(['parent_id'=>$child4['id']])->all();
                        foreach ($children5 as $child5) {
                            echo " -  -  -  -  - ".$child5['username']." : ".$child5['order'];
                            echo "<br>";
                            $children6 = User::find()->where(['parent_id'=>$child5['id']])->all();
                            foreach ($children6 as $child6) {
                                echo " -  -  -  -  -  - ".$child6['username']." : ".$child6['order'];
                                echo "<br>";
                                $children7 = User::find()->where(['parent_id'=>$child6['id']])->all();
                                foreach ($children7 as $child7) {
                                    echo " -  -  -  -  -  - ".$child7['username']." : ".$child7['order'];
                                    echo "<br>";
                                }
                            }
                        }
                    }
                }
            }
        }
        exit;*/
        /*$users = Data::find()->all();
        foreach ($users as $user) {
            $user_db = User::findOne($user['user_id']);
            $user_db->order = $user['order'];
            $user_db->balans = 0;
            $user_db->w_balans = 0;
            $user_db->platform_id = 0;
            $user_db->save();
        }*/
        /*$users = Data::find()->all();
        foreach ($users as $user) {
            $children = Data::find()->where(['parent'=>$user['username']])->andWhere(['<','order',$user['order']])->orderBy('order asc')->one();
            if(!empty($children)){
                $user = Data::findOne($user['id']);
                $order = $user['order'];
                $user->order = $children['order'];
                $user->save();

                $children = Data::findOne($children['id']);
                $children->order = $order;
                $children->save();
            }
        }*/
        /*$users = User::find()->all();
        foreach ($users as $user) {
            if($user['platform_id'] == 1){
                $user_db = User::findOne($user['id']);
                $user_db->w_balans = 0;
                $user_db->save();
            }
            if($user['platform_id'] == 2){
                $user_db = User::findOne($user['id']);
                $matrix = UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
                if($matrix['slots']>3){
                    $user_db->w_balans = 100;
                }else{
                    $user_db->w_balans = 0;
                }

                $user_db->save();
            }
            if($user['platform_id'] == 3){
                $user_db = User::findOne($user['id']);
                $matrix = UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
                if($matrix['slots']>3){
                    $user_db->w_balans = 600;
                }else{
                    $user_db->w_balans = 100;
                }

                $user_db->save();
            }
            if($user['platform_id'] == 4){
                $user_db = User::findOne($user['id']);
                $matrix = UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
                if($matrix['slots']>3){
                    $user_db->w_balans = 1600;
                }else{
                    $user_db->w_balans = 600;
                }

                $user_db->save();
            }
            if($user['platform_id'] == 5){
                $user_db = User::findOne($user['id']);
                $matrix = UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
                if($matrix['slots']>3){
                    $user_db->w_balans = 3600;
                }else{
                    $user_db->w_balans = 1600;
                }

                $user_db->save();
            }
            if($user['platform_id'] == 6){
                $user_db = User::findOne($user['id']);
                $matrix = UserPlatforms::find()->where(['user_id'=>$user['id']])->one();
                if($matrix['slots']>3){
                    $user_db->w_balans = 7600;
                }else{
                    $user_db->w_balans = 3600;
                }

                $user_db->save();
            }
        }*/
        /*$firsts = UserPlatforms::find()->where(['platform_id'=>1])->orderBy('id asc')->all();
        echo "<br>";
        $i = 0;
        foreach ($firsts as $first) {
            $user = User::findOne($first['user_id']);

            $childs = User::find()->where(['parent_id'=>$user['id']])->all();
            if(count($childs)>1){
                $i++;
                echo $user['username'];
                echo " - ";
                echo $first['slots'];
                echo "<br>";
                foreach ($childs as $child) {
                    echo " - ";
                    echo $child['username'];
                    echo "<br>";
                }
                echo "<hr>";
            }

        }
        echo $i;
        exit;*/
        /*$user = User::findOne(9439);
        $user->setPassword('Gcfond2020com');
        $user->generateAuthKey();
        $user->save();*/
        /*$importer = new CSVImporter();

//Will read CSV file
        $importer->setData(new CSVReader([
            'filename' => 'data.csv',
            'charset' => 'UTF-8',
            'fgetcsvOptions' => [
                'delimiter' => ';'
            ]
        ]));
        $news = $importer->getData();

        foreach ($news as $new) {

            $data = new Data();
            $data->order = $new[0];
            $data->username = trim($new[1]);
            $data->parent = trim($new[2]);
            $data->fio = trim($new[3]);
            $data->email = trim($new[4]);
            $data->tel = $new[5];
            $fio = explode(' ',$data->fio);
            if(isset($fio[0])){
                $data->firstname = $fio[0];
            }
            if(isset($fio[1])){
                $data->lastname = $fio[1];
            }
            if(isset($fio[2])){
                $data->secondname = $fio[2];
            }
            $data->save();
        }


        exit;*/
        /*$data = Data::find()->select('username')->distinct()->orderBy('username')->all();
        echo count($data);
        echo "<br>";
        foreach ($data as $datum) {
            $emails = Data::find()->where(['username'=>$datum['username']])->all();
            if(count($emails)>1){
                echo "<br>";
                echo $datum['username'];
                echo "<br>";
            }

        }*/
        /*$data = Data::find()->select('email')->distinct()->orderBy('email')->all();
        echo count($data);
        echo "<br>";
        foreach ($data as $datum) {
            $emails = Data::find()->where(['email'=>$datum['email']])->all();
            if(count($emails)>1){
                echo "<br>";
                echo $datum['email'];
                echo "<br>";
            }

        }*/

       /* $data = Data::find()->all();
        echo count($data);
        echo "<br>";
        foreach ($data as $datum) {
            $user = User::findByEmail($datum['email']);
            if(!empty($user)){
                $dat = Data::findOne($datum['id']);
                $dat->user_id = $user['id'];
                $dat->save();
            }

        }*/
         /*$data = Data::find()->all();

         foreach ($data as $datum) {
             $user = User::findOne($datum['user_id']);
             if(!empty($user)){
                 if(empty($user['parent_id'])){
                     $parent = User::find()->where(['username'=>$datum['parent']])->one();
                     if(!empty($parent)){
                         $user->parent_id = $parent['id'];
                         $user->save();
                     }
                     echo $datum['email'];
                     echo " - ";
                     echo $datum['parent'];
                     echo "<br>";
                 }

             }

         }
         exit;*/
       /*$news = Data::find()->orderBy('order asc')->all();

        foreach ($news as $data) {

            $user_base = User::findByEmail($data['email']);
            if(empty($user_base)){

                if ($user = User::createUser($data['order'],$data['username'],$data['parent'],$data['fio'],$data['email'],$data['tel'],$data['firstname'],$data['lastname'],$data['secondname'])) {
                    $reset = new PasswordResetRequestForm();
                    $reset->course_id = 28;
                    $reset->email = $data['email'];
                    $reset->getToken();

                    $user_course = new UserCourses();
                    $user_course->user_id = $user['id'];
                    $user_course->course_id = 28;
                    $user_course->date = date("d.m.Y H:i");
                    $user_course->save();

                    $data_db = Data::findOne($data['id']);
                    $data_db->user_id = $user['id'];
                    $data_db->save();
                }
            }else{
                $user_base->fio = $data['fio'];
                $user_base->order = $data['order'];
                $user_base->username = $data['username'];
                $user_base->phone = $data['tel'];
                $user_base->firstname = $data['firstname'];
                $user_base->lastname = $data['lastname'];
                $user_base->secondname = $data['secondname'];
                $parent = User::find()->where(['username'=>$data['parent']])->one();
                if(!empty($parent)){
                    $user_base->parent_id = $parent['id'];
                }
                $user_base->save();
                $data_db = Data::findOne($data['id']);
                $data_db->user_id = $user_base['id'];
                $data_db->save();
            }

        }*/
        /*$emails = [
            'asem.zhanekeeva@gmail.com'
        ];
        foreach ($emails as $email) {

            $user_base = User::findByEmail($email);
            if(empty($user_base)){

                if ($user = User::createUser($email)) {
                    $reset = new PasswordResetRequestForm();
                    $reset->course_id = 28;
                    $reset->email = $email;
                    $reset->getToken();

                    $user_course = new UserCourses();
                    $user_course->user_id = $user['id'];
                    $user_course->course_id = 28;
                    $user_course->date = date("d.m.Y H:i");
                    $user_course->save();

                }
            }

        }*/

    }

    /**
     * Finds the Beds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Beds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Beds::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getCustomView($default)
    {
        if (isset($this->customViews[$default])) {
            return $this->customViews[$default];
        } else {
            return $default;
        }
    }
    public function getCustomMailView($default)
    {
        if (isset($this->customMailViews[$default])) {
            return $this->customMailViews[$default];
        } else {
            return '@backend/mail/' . $default;
        }
    }
    public function sendEmailConfirmationMail($view, $toAttribute,$course_id,$user)
    {
        return Yii::$app->mailer->compose(['html' => $view . '-html'], ['user' => $user, 'token' => $user->emailConfirmToken,'course_id'=>$course_id])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($user->emailConfirmToken->{$toAttribute})
            ->setSubject('Доступ к курсу  ' . \Yii::$app->name)
            ->send();
    }
}
