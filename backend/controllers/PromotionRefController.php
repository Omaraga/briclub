<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\BonusTarifs;
use common\models\BonusTarifsNew;
use common\models\PromotionRef;
use common\models\User;
use common\models\UserPromotions;
use common\models\UserPromotionsNew;
use common\models\UserPromotionsRef;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PromotionController implements the CRUD actions for Promotion model.
 */
class PromotionRefController extends Controller
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
        /*echo $time = strtotime('monday this week 4:05');
        echo "<br>";
        echo date('d.m.Y H:i',$time);

        exit;*/
        $time = strtotime('monday this week');
        $current = PromotionRef::find()->where(['<=','start',time()])->andWhere(['>','end',time()])->one();
        if(empty($current)){
            $current = new PromotionRef();
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

        $next = PromotionRef::find()->where(['start'=>$current->end])->one();
        if(empty($next)){
            $next = new PromotionRef();
        }

        $next->start = $current['start'] + 7*24*60*60;
        if($current->id == 1){
            $next->start = 1595786400;
        }
        $next->end = $current['end'] + 7*24*60*60;
        $next->status = 3;
        $next->title = $next->title = "С ".date('d.m.Y H:i',$next->start)." по ".date('d.m.Y H:i',$next->end);
        $next->save();

        $alls = PromotionRef::find()->all();
        foreach ($alls as $all) {
            $prom = PromotionRef::findOne($all['id']);
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
            'query' => PromotionRef::find()->orderBy('start desc'),
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
        $model = PromotionRef::findOne($id);
        if($model->end < time()){
            $users = UserPromotionsRef::find()->where(['pr_id'=>$id])->all();
            $i =0;
            foreach ($users as $user) {
                $user = UserPromotionsRef::findOne($user['id']);

                if($user['status'] == 1) continue;

                $user->status = 1;
                $user->save();
                $user = UserPromotionsRef::findOne($user['id']);
                if($user['sum'] > 0){
                    $i++;
                    User::plusBalans($user['user_id'],$user['sum'],1);
                    $user_db = User::findOne($user['user_id']);
                    $action = new Actions();
                    $action->title = "Бонусы за прошлую неделю";
                    $action->status = 1;
                    $action->user_id = $user_db['id'];
                    $action->time = time();
                    $action->type = 95;
                    $action->sum =$user['sum'];
                    $action->save();
                }

                if($user['shoulder']>0){
                    $next_pr = \common\models\PromotionRef::find()->where(['>','id',$user['pr_id']])->orderBy('id asc')->one();
                    $user_pr_next = \common\models\UserPromotionsRef::find()->where(['user_id'=>$user['user_id'],'pr_id'=>$next_pr->id])->orderBy('id desc')->one();
                    if(empty($user_pr_next)){
                        $user_pr_next = new \common\models\UserPromotionsRef();
                    }
                    $user_pr_next->user_id = $user['user_id'];
                    $user_pr_next->status = 2;
                    $user_pr_next->pr_id = $next_pr->id;
                    $user_pr_next->shoulder_next = $user['shoulder'];
                    if($user['shoulder'] == 1){
                        $user_pr_next->shoulder1 = $user['res'];
                        $user_pr_next->shoulder2 = 0;
                    }elseif ($user['shoulder'] == 2){
                        $user_pr_next->shoulder2 = $user['res'];
                        $user_pr_next->shoulder1 = 0;
                    }
                    $user_pr_next->all = $user['res'];
                    $user_pr_next->sum = 0;
                    $user_pr_next->res_next = $user['res'];
                    $user_pr_next->save();
                }

            }
        }
        Yii::$app->getSession()->setFlash('success', Yii::t('users', $i));
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
        $model = new PromotionRef();

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
        if (($model = PromotionRef::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
