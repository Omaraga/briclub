<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\Messages;
use common\models\Tickets;
use common\models\User;
use Yii;
use common\models\Bills;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BillsController implements the CRUD actions for Bills model.
 */
class BillsController extends Controller
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
     * Lists all Bills models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Bills::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bills model.
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
     * Creates a new Bills model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bills();
        $users = User::find()->all();
        if (Yii::$app->request->get('ticket_id')){
            $model->ticket_id = Yii::$app->request->get('ticket_id');
        }
        if ($model->load(Yii::$app->request->post())) {
            $id = Bills::find()->orderBy(['id' => SORT_DESC])->one();

            if($id == null){
                $id = 1;
            }
            else{
                $id = $id['id'];
            }
            $code = $id * 1992 * 29 * 4;
            
            $model->code = $code;
            $model->created_at = time();
            $model->status = 2;
            $model->receiver_id = 1;
            $model->sender_id = User::findOne(['username' => Yii::$app->request->post('sender')])['id'];

            $model->save(false);

            $action = new Actions();
            $action->status = 1;
            $action->type = 64;
            if (isset($model->ticket_id) && !is_null($model->ticket_id)) {
                $action->title = 'Выставлен счет за оплату услуги техподдержки';
            }else{
                $action->title = $model->comment;
            }

            $action->user_id = $model->sender_id; //sender
            $action->user2_id = $model->receiver_id; //receiver
            $action->tokens = $model->sum;
            $action->time = time();
            $action->save(false);
            if (isset($model->ticket_id) && !is_null($model->ticket_id)){
                $ticket = Tickets::findOne($model->ticket_id);
                $ticket->payment_status = Tickets::PAYMENT_STATUS_NEED_PAY;
                $ticket->bill_id = $model->id;
                $ticket->status = 2;
                $ticket->save(false);

                /*Создаем сообщение для оплаты*/
                $messageModel = new Messages();
                $messageModel->time = time();
                $messageModel->user_id = 1;
                $messageModel->is_payment = 1;
                $messageModel->ticket_id = $model->ticket_id;
                $messageModel->text = '<p><b>Счет на оплату '
                    .'№'.$model->code.' от '.date('d.m.Y',time()).' г.</b><br>'
                    .$model->comment.'<br>Сумма услуги:<b> '.$model->sum.'CV</b>'
                    .'</p>';
                $messageModel->save();
                return $this->redirect(['/tickets/view?id='.$model->ticket_id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Updates an existing Bills model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $users = User::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->status = 2;
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'users' => $users
        ]);
    }

    /**
     * Deletes an existing Bills model.
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
     * Finds the Bills model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bills the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bills::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
