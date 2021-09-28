<?php


namespace backend\controllers;


use common\models\Actions;
use common\models\TransfersList;
use common\models\TransfersSearch;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TransfersListController extends Controller
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
     * Lists all UsersList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransfersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsersList model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UsersList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TransfersList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UsersList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UsersList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
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
     * Finds the UsersList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransfersList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransfersList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}