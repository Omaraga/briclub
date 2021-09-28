<?php

namespace backend\controllers;

use backend\models\TransfersForm;
use common\models\Tokens;
use common\models\User;
use TheSeer\Tokenizer\Token;
use Yii;
use common\models\Actions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepositController implements the CRUD actions for Actions model.
 */
class DepositTokensController extends Controller
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
            'query' => Actions::find()->where(['type'=>63]),
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
                    'query' => Actions::find()->where(['type'=>63,'user_id'=>$user['id']]),
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
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new TransfersForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $action = new Actions();
            $user = User::find()->where(['username'=>$model->username])->one();
            $action->type = 63;
            $action->user_id = $user['id'];
            $action->time = time();
            $action->status = 1;
            $action->tokens = $model->sum;
            $action->target = $model->target;
            $action->comment = $model->comment;
            $action->title = "Пополнение администратором";
            $action->admin_id = Yii::$app->user->id;
            $action->save();
            $user = User::findOne($user['id']);
            $userTokens = Tokens::findone(['user_id' => $user->id]);
            $adminTokens = Tokens::findone(['wallet_type' => 1, 'user_id' => 1]);
            if($userTokens == null){
                $userTokens = new Tokens();
                $userTokens->balans = 0;
                $userTokens->user_id = $user->id;
            }
            $userTokens->balans += $action->tokens;
            $adminTokens->balans -= $action->tokens;
            $userTokens->save();
            $adminTokens->save();

            $user->save();
            return $this->redirect('/deposit-tokens');
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
