<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\BonusTarifs;
use common\models\BonusTarifsNew;
use common\models\User;
use common\models\UserPromotions;
use common\models\UserPromotionsNew;
use Yii;
use common\models\PromotionNew;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PromotionController implements the CRUD actions for Promotion model.
 */
class PromotionNewController extends Controller
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
        /*echo $time = strtotime('27 july');
        echo "<br>";
        echo date('d.m.Y H:i',$time);

        exit;*/
        $time = strtotime('monday this week');
        $current = PromotionNew::find()->where(['<=','start',time()])->andWhere(['>','end',time()])->one();
        if(empty($current)){
            $current = new PromotionNew();
        }

        if($current->id == 1){
            $current->start = 1593626400;
            $current->end = 1595786400;
        }else{
            $current->start = $time;
            $current->end = $current->start + 7*24*60*60;
        }

        $current->title = "С ".date('d.m.Y H:i',$current->start)." по ".date('d.m.Y H:i',$current->end);
        $current->status = 1;
        $current->save();

        $next = PromotionNew::find()->where(['start'=>$current->end])->one();
        if(empty($next)){
            $next = new PromotionNew();
        }

        $next->start = $current['start'] + 7*24*60*60;
        if($current->id == 1){
            $next->start = 1595786400;
        }
        $next->end = $current['end'] + 7*24*60*60;
        $next->status = 3;
        $next->title = $next->title = "С ".date('d.m.Y H:i',$next->start)." по ".date('d.m.Y H:i',$next->end);
        $next->save();

        $alls = PromotionNew::find()->all();
        foreach ($alls as $all) {
            $prom = PromotionNew::findOne($all['id']);
            if(time() >= $prom->start and time() < $prom->end){
                $prom->status = 1;
            }
            if(time() > $prom->end){
                $prom->status = 2;
            }
            if(time() < $prom->start){
                $prom->status = 3;
            }
            $prom->save();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => PromotionNew::find()->orderBy('start desc'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        $model = PromotionNew::findOne($id);
        if($model->end < time()){
            $users = UserPromotionsNew::find()->where(['pr_id'=>$id])->all();
            foreach ($users as $user) {
                $user = UserPromotionsNew::findOne($user['id']);

                if($user['status'] == 2) continue;
                $user->status = 2;
                $user->save();
                $user_db = User::findOne($user['user_id']);
                User::plusBalans($user['user_id'],BonusTarifsNew::findOne($user['tarif_id'])['sum'],1);

                $action = new Actions();
                $action->title = "Бонусы за прошлую неделю";
                $action->status = 1;
                $action->user_id = $user_db['id'];
                $action->time = time();
                $action->type = 94;
                $action->sum = BonusTarifsNew::findOne($user['tarif_id'])['sum'];
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
        $model = new PromotionNew();

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
        if (($model = PromotionNew::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
