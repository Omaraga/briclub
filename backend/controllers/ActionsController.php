<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use common\models\Actions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActionsController implements the CRUD actions for Actions model.
 */
class ActionsController extends Controller
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
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Actions::find()->where(['type'=>104]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        $model = new Actions();

        if ($model->load(Yii::$app->request->post())) {
            $model->time = time();
            $model->type = 104;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    const POPOLNENIE = 0;
    const DOHOD = 1;
    const POSTUPLENIE = 2;
    const PEREVOD = 3;
    public function actionStructurePayments($userId = null, $tab = 0){
        $dataProvider = null;
        $user = null;
        $structureSize = 0;
        $amountSum = 0;
        $amountUserSum = 0;

        if ($userId){
            $user = User::findOne($userId);
            $referals = \common\models\Referals::find()->select('id, user_id')->where(['parent_id'=>$user->id])->asArray()->orderBy('level asc')->all();
            $refIdsArray = [];
            $refIdsArray[] = $userId;

            foreach ($referals as $ref){
                $refIdsArray[] = $ref['user_id'];
            }
            $actionTypes = [];
            $tab = intval($tab);
            if ($tab == self::POPOLNENIE){
                $actionTypes = [5, 6, 8, 12, 102, 103];
            }else if($tab == self::DOHOD){
                $actionTypes = [7, 105];
            }else if($tab == self::POSTUPLENIE){
                $actionTypes = [4];
            }else if($tab == self::PEREVOD){
                $actionTypes = [3];
            }
            $structureSize = sizeof($referals);
            $amountSum = Actions::find()->select('id, sum')->where(['type' => $actionTypes])->andWhere(['user_id' => $refIdsArray])->sum('sum');
            $amountUserSum = Actions::find()->select('id, sum')->where(['type' => $actionTypes])->andWhere(['user_id' => $user->id])->sum('sum');

            $dataProvider = new ActiveDataProvider([
                'query' => Actions::find()->where(['type' => $actionTypes])->andWhere(['user_id' => $refIdsArray]),
            ]);

        }else{
            $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('structure',[
            'dataProvider'=> $dataProvider,
            'user' => $user,
            'tab' => $tab,
            'structureSize' => $structureSize,
            'amountSum' => $amountSum,
            'amountUserSum' => $amountUserSum,
        ]);
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
