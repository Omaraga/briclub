<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\User;
use Yii;
use common\models\Withdraws;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\export\ExportMenu;
/**
 * WithdrawsController implements the CRUD actions for Withdraws model.
 */
class WithdrawsController extends Controller
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
     * Lists all Withdraws models.
     * @return mixed
     */
    public function actionIndex($username=null,$status=null,$wallet=null)
    {



        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $error = null;
        $dataProvider = new ActiveDataProvider([
            'query' => Withdraws::find()->orderBy(['time' => SORT_DESC]),
//            'sort' => [
//                'defaultOrder' => [
//                    'id' => SORT_DESC,
//                ]
//            ],
        ]);





        if(!empty($username)){
            $user = \common\models\User::find()->where(['username'=>$username])->one();

            if(empty($user)){
                $username = $user['username'];
                $error = "Такого пользователя нет!";
            }else{
                $dataProvider = new ActiveDataProvider([
                    'query' => Withdraws::find()->where(['user_id'=>$user['id']])->orderBy(['time' => SORT_DESC]),
                ]);
            }

        }
        if(!empty($status)){
            $dataProvider = new ActiveDataProvider([
                'query' => Withdraws::find()->where(['status'=>$status])->orderBy(['time' => SORT_DESC]),
            ]);

        }
		
		if(!empty($wallet)){
            $dataProvider = new ActiveDataProvider([
                'query' => Withdraws::find()->where(['account'=>$wallet])->orderBy(['time' => SORT_DESC]),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'error' => $error,
        ]);
    }

    /**
     * Displays a single Withdraws model.
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

    public function actionExport()
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Withdraws::find(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('export', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Withdraws model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('superadmin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new Withdraws();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Withdraws model.
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
        $model->admin_id = Yii::$app->user->id;
        if($model->status == 1 or $model->status == 2  or $model->status == 4){
            $this->redirect(['view', 'id' => $model->id]);
        }
        $sum1 = $model->sum;
        if ($model->load(Yii::$app->request->post())) {
            if($model->sum > $sum1){
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Сумма не может быть больше изначального значения! '));
                return $this->redirect('/withdraws/update?id='.$model->id);
            }
            if($model->status == 3){
                $model->sum = $sum1;
            }
            if($model->status == 2){
                $model->sum = $sum1;
                $user = User::findOne($model->user_id);
                $user->w_balans = $user->w_balans + $model->sum;
                $user->save();
                $action = new Actions();
                $action->status = 1;
                $action->type = 22;
                $action->sum = $model->sum;
                $action->time = time();
                $action->title = "Отмена вывода";
                $action->user_id = $model->user_id;
                $action->save();
            }
            if($sum1 > $model->sum){
                $model->sum2 = $model->sum*0.99;
                $model->fee = $model->sum*0.01;
                $user = User::findOne($model->user_id);
                $user->w_balans = $user->w_balans + $sum1 - $model->sum;
                $user->save();
                $action = new Actions();
                $action->status = 1;
                $action->type = 22;
                $action->sum = $sum1 - $model->sum;
                $action->time = time();
                $action->title = "Частичная отмена вывода";
                $action->user_id = $model->user_id;
                $action->save();
            }
            if($model->system_id == 2 and $model->status == 1){


                $action = new Actions();
                $action->status = 1;
                $action->type = 2;
                $action->sum = $model->sum;
                $action->time = time();
                $action->title = "Вы вывели деньги через Perfect Money";
                $action->user_id = $model->user_id;
                $action->save();

                $payee = $model->account;
                $amount = $model->sum2;
                $pay_id = $model->id;
                $url = 'https://perfectmoney.com/acct/confirm.asp?AccountID='.Yii::$app->params['AccountID'].'&PassPhrase='.Yii::$app->params['PassPhrase'].'&Payer_Account='.Yii::$app->params['PayerAccount'].'&Payee_Account='.$payee.'&Amount='.$amount.'&PAY_IN=1&PAYMENT_ID='.$pay_id;

                $f=fopen($url, 'rb');

                if($f===false){
                    echo 'error openning url';
                }

// getting data
                $out=array(); $out="";
                while(!feof($f)) $out.=fgets($f);

                fclose($f);

// searching for hidden fields
                if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){
                    echo 'Ivalid output';
                    exit;
                }

                /*$ar="";
                foreach($result as $item){
                    $key=$item[1];
                    $ar[$key]=$item[2];
                }*/

                if(isset($result[0][1])){
                    $result_text = $result[0][1];
                }
                if(isset($result[0][2])){
                    $result_text2 =  $result[0][2];
                }
                if($result_text == "ERROR"){
                    $model->status = 3;
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! '.$result_text2));
                }else{
                    $model->status = 1;
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Платеж успешно произведен!'));
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Withdraws model.
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
     * Finds the Withdraws model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Withdraws the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Withdraws::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
