<?php

namespace backend\controllers;

use backend\models\TransfersForm;
use common\models\ActionTypes;
use common\models\User;
use common\models\Withdraws;
use Yii;
use common\models\Actions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepositController implements the CRUD actions for Actions model.
 */
class TransfersController extends Controller
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
            'query' => Actions::find()->where(['type'=>3]),
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
                    'query' => Actions::find()->where(['type'=>3,'user_id'=>$user['id']]),
                ]);
            }

        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
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

    public function actionBack($id=null)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        if(!empty($id)){
            $model = $this->findModel($id);
            if($model->status != 2){
                $model->status = 2;
                $model->save();
                $user1 = User::findOne($model->user_id);
                $user2 = User::findOne($model->user2_id);
                $user1->w_balans = $user1->w_balans + $model->sum;
                $user1->save();
                $action = new Actions();
                $action->status = 1;
                $action->type = 33;
                $action->sum = $model->sum;
                $action->time = time();
                $action->title = "Отмена перевода пользователю ".$user2['username'];
                $action->user_id = $model->user_id;
                $action->user2_id = $model->user2_id;
                $action->save();

                $user2->w_balans = $user2->w_balans - $model->sum;
                $user2->save();
                $action = new Actions();
                $action->status = 1;
                $action->type = 44;
                $action->title = "Отмена перевода от пользователя ".$user1['username'];
                $action->time = time();
                $action->sum = $model->sum;
                $action->user_id = $model->user2_id;
                $action->user2_id = $model->user_id;
                $action->save();
            }
        }


        return $this->redirect(['index']);
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
}
