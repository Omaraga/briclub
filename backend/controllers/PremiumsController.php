<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\logic\PremiumsManager;
use common\models\PremiumTariffs;
use Yii;
use common\models\Premiums;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PremiumsController implements the CRUD actions for Premiums model.
 */
class PremiumsController extends Controller
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
     * Lists all Premiums models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Premiums::find()->where(['!=', 'tariff_id', 7])->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Premiums model.
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
     * Creates a new Premiums model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = Premiums::findOne(['user_id' => $id]);
        if($model == null){
            $model = new Premiums();
        }

        if ($model->load(Yii::$app->request->post())) {

            $status = PremiumsManager::addPremium($model->tariff_id, $id);

            if($status == 1){
                $action = new Actions();
                $action->user_id = $id;
                $action->time = time();
                $action->type = 83;
                $action->status = 1;
                if ($actionTitle = PremiumsManager::getActionTitleByTariffId($model->tariff_id)){
                    $action->title = $actionTitle;
                }else{
                    $action->title = "Вы успешно активировали Premium-аккаунт";
                }
                $action->save();
                Yii::$app->session->setFlash('success', 'Premium-аккаунт успешно активирован!');
            }
            else{
                Yii::$app->session->setFlash('danger', 'На аккаунте уже активирован Premium навсегда!');
            }

            return $this->redirect(['/users/view', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Premiums model.
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
     * Deletes an existing Premiums model.
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
     * Finds the Premiums model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Premiums the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Premiums::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
