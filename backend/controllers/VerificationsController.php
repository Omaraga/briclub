<?php

namespace backend\controllers;
use common\models\Verifications;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VerificationsController implements the CRUD actions for Verifications model.
 */
class VerificationsController extends Controller
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
     * Lists all Verifications models.
     * @return mixed
     */
    public function actionIndex()
    {

        $dataProviderAll = new ActiveDataProvider([
            'query' => Verifications::find()->orderBy(['id' => SORT_DESC]),
        ]);
        $dataProviderWait = new ActiveDataProvider([
            'query' => Verifications::find()->where(['stage'=>[Verifications::STAGE_USER_DATA_WAIT_VALID,Verifications::STAGE_ADDRESS_WAIT_VALID]])->orderBy(['time' => SORT_DESC]),
        ]);
        $dataProviderCanceled = new ActiveDataProvider([
            'query' => Verifications::find()->where(['stage'=>[Verifications::STAGE_MODIFY_USER_DATA, Verifications::STAGE_ADDRESS_MODIFY]])->orderBy(['time' => SORT_DESC]),
        ]);
        $dataProviderComplete = new ActiveDataProvider([
            'query' => Verifications::find()->where(['stage'=>[Verifications::STAGE_ALL_VALIDATED, Verifications::STAGE_READY_TO_VALID_ADDRESS]])->orderBy(['time' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProviderAll' => $dataProviderAll,
            'dataProviderWait' => $dataProviderWait,
            'dataProviderCanceled' => $dataProviderCanceled,
            'dataProviderComplete' => $dataProviderComplete,
        ]);
    }

    /**
     * Displays a single Verifications model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {

            $model->changeStage();
            if($model->save()){
                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Отправлено!'));
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте повторить!'));
            }
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Verifications model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Verifications();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Verifications model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $user = User::findOne($model->user_id);
            $user->verification = 1;
            $user->save();
            $model->changeStage();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Verifications model.
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

    /**
     * Finds the Verifications model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Verifications the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Verifications::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
