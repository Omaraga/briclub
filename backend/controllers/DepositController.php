<?php

namespace backend\controllers;

use backend\models\TransfersForm;
use backend\models\forms\ConvertPVForm;
use common\models\Deposit;
use common\models\User;
use Yii;
use common\models\Actions;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepositController implements the CRUD actions for Actions model.
 */
class DepositController extends Controller
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
     * Lists all Actions models.
     * @return mixed
     */
    public function actionIndex($username=null)
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $error = null;
        $dataProvider = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>5]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider2 = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>[6,66]]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider3 = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>[102, 122]]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider4 = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>[8,88]]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $dataProvider5 = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>[12]]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        if(!empty($username)){
            $user = \common\models\User::find()->where(['username'=>$username])->one();

            if(empty($user)){
                $username = $user['username'];
                $error = "Такого пользователя нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => Actions::find()->where(['type'=>5,'user_id'=>$user['id']]),
                ]);
                $dataProvider2 = new ActiveDataProvider([
                    'query' => Actions::find()->where(['type'=>6,'user_id'=>$user['id']]),
                ]);
                $dataProvider3 = new ActiveDataProvider([
                    'query' => Actions::find()->where(['type'=>[102, 122],'user_id'=>$user['id']]),
                ]);
                $dataProvider4 = new ActiveDataProvider([
                    'query' => Actions::find()->where(['type'=>[8,88],'user_id'=>$user['id']]),
                ]);
                $dataProvider5 = new ActiveDataProvider([
                    'query' => Actions::find()->where(['type'=>[12],'user_id'=>$user['id']]),
                ]);
            }

        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
            'dataProvider4' => $dataProvider4,
            'dataProvider5' => $dataProvider5,
            'error' => $error,
        ]);
    }

    /**
     * Displays a single Actions model.
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

    /**
     * Creates a new Actions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new TransfersForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $action = new Actions();
            $user = User::find()->where(['username'=>$model->username])->one();
            $action->type = 5;
            $action->user_id = $user['id'];
            $action->time = time();
            $action->status = 1;
            $action->sum = $model->sum;
            $action->target = $model->target;
            $action->comment = $model->comment;
            $action->title = "Пополнение администратором";
            $action->admin_id = Yii::$app->user->id;
            $action->save();
            $user = User::findOne($user['id']);
            $user->w_balans = $user->w_balans + $action->sum;
            $user->save();
            return $this->redirect('/deposit');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Actions model.
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Actions model.
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
    public function actionConvert() {
        $model = new ConvertPVForm();
        $username = Yii::$app->request->get('username');
        $user = User::find()->where(['username' => $username])->one();
        $model->load(Yii::$app->request->post());
        if ($model->validate()) {
            $convert = DepositAccountsController::actionValidateCheck($user, $model);
            return $this->render('convert', ['model' => $model]);
        }
    }

    /**
     * Finds the Actions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Actions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Actions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
//    public function actionUpdateType()
//    {
//        $actions = Actions::find()->where(['type'=>122])->andWhere(['sum'=> null])->all();
//        foreach ($actions as $action)
//        {
//
//            $action->sum = 103;
//            $action->comment = "Активация через VISA/MASTERCARD";
//            $action->save(false);
//        }
//
//    }
}
