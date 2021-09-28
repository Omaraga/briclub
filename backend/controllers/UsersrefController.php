<?php

namespace backend\controllers;

use app\models\Activities;
use app\models\Parents;
use app\models\UserInfo;
use backend\models\ResetPasswordForm;
use common\models\Confirms;
use common\models\UserCourses;
use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersrefController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex($username = null,$email=null,$phone=null,$fio=null,$platform=null,$from=null,$to=null,$balans=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }


        $user = null;
        $userinfo = null;
        $sponsor = null;
        $activities = null;
        $error = null;
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['not in','id',1])->andWhere(['newmatrix'=>1]),
            'sort' => [
                'defaultOrder' => [
                    'time_personal' => SORT_DESC,
                ]
            ],
        ]);
        if(!empty($balans)){
            if($balans == 1){
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','w_balans',0])->andWhere(['newmatrix'=>1]),
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['<','w_balans',0])->andWhere(['newmatrix'=>1]),
                ]);
            }
        }
        if(!empty($from)){
            $user = \common\models\User::find()->where(['username'=>$username])->andWhere(['newmatrix'=>1])->one();
            $time = strtotime($from);
            if(!empty($to)){
                $time2 = strtotime($to);
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','created_at',$time])->andWhere(['<=','created_at',$time2])->andWhere(['newmatrix'=>1]),
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','created_at',$time])->andWhere(['newmatrix'=>1]),
                ]);
            }
        }
        if(!empty($to)){
            $user = \common\models\User::find()->where(['username'=>$username])->andWhere(['newmatrix'=>1])->one();
            $time2 = strtotime($to);
            if(!empty($from)){
                $time = strtotime($from);
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['>=','created_at',$time])->andWhere(['<=','created_at',$time2])->andWhere(['newmatrix'=>1]),
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['<=','created_at',$time2])->andWhere(['newmatrix'=>1]),
                ]);
            }
        }

        if(!empty($username)){
            $user = \common\models\User::find()->where(['username'=>$username])->andWhere(['newmatrix'=>1])->one();

            if(empty($user)){
                $username = $user['username'];
                $error = "Такого пользователя нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['username'=>$user['username']])->andWhere(['newmatrix'=>1]),
                ]);
            }

        }
        if(!empty($platform)){
            if($platform == 11){
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['<','platform_id',1])->orWhere(['platform_id'=>null])->andWhere(['newmatrix'=>1]),
                ]);
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['platform_id'=>$platform])->andWhere(['newmatrix'=>1]),
                ]);
            }

        }
        if(!empty($email)){
            $user = \common\models\User::find()->where(['email'=>$email])->andWhere(['newmatrix'=>1])->one();

            if(empty($user)){
                $email = $user['email'];
                $error = "Такого email-а нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['email'=>$user['email']])->andWhere(['newmatrix'=>1]),
                ]);
            }

        }

        if(!empty($phone)){
            $user = \common\models\User::find()->where(['phone'=>$phone])->andWhere(['newmatrix'=>1])->one();

            if(empty($user)){
                $phone = $user['phone'];
                $error = "Такого телефона нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['phone'=>$user['phone']])->andWhere(['newmatrix'=>1]),
                ]);
            }

        }
        if(!empty($fio)){
            $user = \common\models\User::find()->where(['fio'=>$fio])->andWhere(['newmatrix'=>1])->one();

            if(empty($user)){
                $fio = $user['fio'];
                $error = "Такого ФИО нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['fio'=>$user['fio']])->andWhere(['newmatrix'=>1]),
                ]);
            }

        }

        return $this->render('index',[
            'balans' =>$balans,
            'username' =>$username,
            'email' =>$email,
            'user' => $user,
            'fio' => $fio,
            'phone' => $phone,
            'from' => $from,
            'to' => $to,
            'userinfo' => $userinfo,
            'activities' => $activities,
            'platform' => $platform,
            'sponsor' => $sponsor,
            'error' => $error,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionAccess($user_id,$course_id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $user_course = UserCourses::find()->where(['user_id'=>$user_id,'course_id'=>$course_id])->one();
        if(!empty($user_course)){
            $user_course->delete();
        }else{
            $user_course = new UserCourses();
            $user_course->user_id = $user_id;
            $user_course->course_id = $course_id;
            $user_course->date = date("d.m.Y H:i");
            $user_course->save();
        }
        return $this->redirect('/users/view?id='.$user_id);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::findOne($model->id);
            if(!empty($model->email)){
                $user->email = $model->email;
            }
            if(!empty($model->password)){
                $user->setPassword($model->password);
                $user->generateAuthKey();
            }

            $user->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionConfirm($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $confirm = Confirms::find()->where(['user_id'=>$id])->one();
        if(empty($confirm)){
            $confirm = new Confirms();
            $confirm->user_id = $id;
            $confirm->status = 1;
            $confirm->save();
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
