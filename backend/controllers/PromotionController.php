<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\BonusTarifs;
use common\models\User;
use common\models\UserPromotions;
use Yii;
use common\models\Promotion;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PromotionController implements the CRUD actions for Promotion model.
 */
class PromotionController extends Controller
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
     * Lists all Promotion models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $maksimum = Actions::find()->where(['type'=>94])->all();
        $binar = Actions::find()->where(['type'=>95])->all();

        return $this->render('index', [
            'binar' => $binar,
            'maksimum' => $maksimum,
        ]);
    }

    /**
     * Displays a single Promotion model.
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

    public function actionEnd($id)
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = Promotion::findOne($id);
        if($model->end < time()){
            $users = UserPromotions::find()->where(['pr_id'=>$id])->all();
            foreach ($users as $user) {
                $user = UserPromotions::findOne($user['id']);

                if($user['status'] == 2) continue;
                $user->status = 2;
                $user->save();
                $user_db = User::findOne($user['user_id']);
                $user_db->w_balans = $user_db->w_balans + BonusTarifs::findOne($user['tarif_id'])['sum'];
                $user_db->save();
                $action = new Actions();
                $action->title = "Бонусы за прошлую неделю";
                $action->status = 1;
                $action->user_id = $user_db['id'];
                $action->time = time();
                $action->type = 11;
                $action->sum = BonusTarifs::findOne($user['tarif_id'])['sum'];
                $action->save();
            }
        }

        $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Creates a new Promotion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new Promotion();

        if ($model->load(Yii::$app->request->post())) {

            $model->end = $model->start + 7*24*60*60;
            $model->title = "С ".date('d.m.Y H:i',$model->start)." по ".date('d.m.Y H:i',$model->end);
            if(time() >= $model->start and time() < $model->end){
                $model->status = 1;
            }
            if(time() > $model->end){
                $model->status = 2;
            }
            if(time() < $model->start){
                $model->status = 3;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Promotion model.
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

        if ($model->load(Yii::$app->request->post())) {
            $model->end = $model->start + 7*24*60*60;
            $model->title = "С ".date('d.m.Y H:i',$model->start)." по ".date('d.m.Y H:i',$model->end);
            if(time() >= $model->start and time() < $model->end){
                $model->status = 1;
            }
            if(time() > $model->end){
                $model->status = 2;
            }
            if(time() < $model->start){
                $model->status = 3;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Promotion model.
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
     * Finds the Promotion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Promotion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promotion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
