<?php

namespace backend\controllers;

use common\models\Actions;
use common\models\ActionTypes;
use common\models\logic\PremiumsManager;
use common\models\Messages;
use common\models\Premiums;
use common\models\User;
use common\models\Verifications;
use Yii;
use common\models\Tickets;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\TicketSearchForm;



/**
 * TicketsController implements the CRUD actions for Tickets model.
 */
class TicketsController extends Controller
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
     * Lists all Tickets models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!User::isAccess('admin')){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        $searchModel = new TicketSearchForm();
        $userNameCondition = [];
        $dateStartCondition = [];
        $dateEndCondition = [];
        $idCondition = [];



        $users = ArrayHelper::getColumn(User::find()->select('username')->asArray()->all(),'username');
        if ($searchModel->load(Yii::$app->request->post())){
            if (strlen($searchModel->username) > 0){
                $searchUser = User::find()->select('id')->where(['username'=>$searchModel->username])->asArray()->one();
                if (!is_null($searchUser)){
                    $userNameCondition = ['user_id'=>$searchUser['id']];
                }
            }
            if(strlen($searchModel->id) > 0){
                $searchTicket = Tickets::find()->where(['id'=>$searchModel->id])->asArray()->one();
                if (!is_null($searchTicket)){
                    $idCondition = ['id'=>$searchTicket['id']];
                }
            }
            if (isset($searchModel->date_start) && strlen($searchModel->date_start) > 0){
                $searchModel->setTimes();
                $dateStartCondition = ['>=', 'time', $searchModel->time_start];
            }
            if (isset($searchModel->date_end) && strlen($searchModel->date_end) > 0){
                $searchModel->setTimes();
                $dateEndCondition = ['<=', 'time', $searchModel->time_end];
            }
        }
        $dataProviderWork = new ActiveDataProvider([
            'query' => Tickets::find()->where(['status'=>3])->andWhere($userNameCondition)->andWhere($idCondition)->andWhere($dateStartCondition)->andWhere($dateEndCondition)->orderBy('id desc'),
        ]);
        $dataProviderAnswered = new ActiveDataProvider([
            'query' => Tickets::find()->where(['status'=>2])->andWhere($userNameCondition)->andWhere($idCondition)->andWhere($dateStartCondition)->andWhere($dateEndCondition)->orderBy('id desc'),
        ]);
        $dataProviderEnd = new ActiveDataProvider([
            'query' => Tickets::find()->where(['status'=>1])->andWhere($userNameCondition)->andWhere($idCondition)->andWhere($dateStartCondition)->andWhere($dateEndCondition)->orderBy('id desc'),
        ]);


        return $this->render('index', [
            'dataProviderWork' => $dataProviderWork,
            'dataProviderAnswered' => $dataProviderAnswered,
            'dataProviderEnd' => $dataProviderEnd,
            'searchModel'=>$searchModel,
            'users'=>$users,
        ]);
    }

    /**
     * Displays a single Tickets model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $messageForm = new \frontend\models\forms\MessageForm();
        if ($messageForm->load(Yii::$app->request->post()) && $messageForm->validate()) {
            $ticket = Tickets::findOne($id);
            $ticket->status = 2;
            if (!isset($ticket->end_time) || $ticket->end_time == null){
                $ticket->end_time = strtotime ('3 weekdays')+60*60*12;
            }
            $ticket->save();

            $message = new Messages();
            $message->time = time();
            $message->user_id = 1;
            $message->ticket_id = $ticket['id'];
            $message->text = $messageForm->text;

            $file = UploadedFile::getInstance($messageForm, 'file');
            $link = null;
            if ($file && $file->tempName) {
                $messageForm->file = $file;
                if ($messageForm->validate(['file'])) {

                    $rand = rand(900000,9000000);
                    $dir = Yii::getAlias('@frontend/web/docs/tickets/');
                    $dir2 = Yii::getAlias('docs/tickets/');
                    $fileName = $rand . '.' . $messageForm->file->extension;
                    $messageForm->file->saveAs($dir . $fileName);
                    $messageForm->file = $fileName; // без этого ошибка
                    $link = '/'.$dir2 . $fileName;
                }
            }
            $message->link = $link;

            if($message->save()){
                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Сообщение отправлено!'));
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте повторить!'));
            }

        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'messageForm' => $messageForm,
        ]);
    }

    /**
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tickets();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tickets model.
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
     * Deletes an existing Tickets model.
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
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tickets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tickets::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionDevDesktop(){
//        $status = PremiumsManager::addPremium(6, 32849);
//
//        if($status == 1){
//            $action = new Actions();
//            $action->user_id = 32849;
//            $action->time = time();
//            $action->type = 81;
//            $action->status = 1;
//            $action->title = "Вы успешно активировали Premium-аккаунт";
//            $action->save();
//        }die;

//        $users = User::find()->select('id')->where(['activ' => 1])->asArray()->all();
//        $counter = 0;
//        $premUsername = [];
//        $premiums = Premiums::find()->select('id, is_active, tariff_id, user_id')->asArray()->all();
//        $premiumArray = [];
//        foreach ($premiums as $item){
//            $premiumArray[$item['user_id']] = $item;
//        }
//        foreach ($users as $item){
//            if(!array_key_exists($item['id'], $premiumArray)){
//                PremiumsManager::addPremium(7, $item['id']);
//                $counter++;
//                $premUsername[] = $item['id'];
//            }
//
//        }
//        echo $counter;
//        echo '<hr>';
//        echo '<pre>';
//        print_r($premUsername);
//        echo '</pre>';
//        die;


        $dataProviderWait = new ActiveDataProvider([
            'query' => Tickets::find()->where(['is_to_dev'=>1, 'dev_status'=>[0,1]])->orderBy('id desc'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $dataProviderWork = new ActiveDataProvider([
            'query' => Tickets::find()->where(['is_to_dev'=>1, 'dev_status'=>2])->orderBy('id desc'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $dataProviderReady = new ActiveDataProvider([
            'query' => Tickets::find()->where(['is_to_dev'=>1, 'dev_status'=>3])->orderBy('id desc'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('devdesktop', [
            'dataProviderWait' => $dataProviderWait,
            'dataProviderReady'=>$dataProviderReady,
            'dataProviderWork' =>$dataProviderWork,
        ]);
    }

    private function sendTelegramNotification($notification, $localPass = null)
    {
        //https://api.telegram.org/bot1839487209:AAEEKRjTJ2Ob7qzGwlKIhkizAVefp00HkO0/getUpdates - узнать id чата
        if ($localPass == 'ShanyrakPlus+'){
            $token = "1839487209:AAEEKRjTJ2Ob7qzGwlKIhkizAVefp00HkO0";
            $chat_id = "-475592752";
            $txt = $notification;
            $url = "https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}";
            try {
                $sendTelegram = fopen($url, "r");
                if (!$sendTelegram){
                    throw new Exception('Error');
                }
            }catch (\Exception $exception){
                return false;
            }
        }else{
            return false;
        }



    }
    public function actionUpdateMessage()
    {
        if (Yii::$app->request->isPost){
            $ticketId = Yii::$app->request->post('ticketId');
            $message = Yii::$app->request->post('message');
            $userId = Yii::$app->request->post('from');
            $devStatus = Yii::$app->request->post('status');
            $ticket = Tickets::findOne($ticketId);
            $user = User::findOne($userId);

            if (isset($ticket) && isset($user) && isset($message)){
                $all_access = \common\models\Access::find()->where(['developer'=>1])->all();
                $access = [];
                $userRole = null;
                foreach ($all_access as $one_access) {
                    $access[] = $one_access['username'];
                }
                if(in_array($user['username'], $access)){
                    $userRole = 'developer';
                }else{
                    $userRole = 'admin';
                }
                if ($devStatus == 'toWork') {
                    $ticket->dev_status = 1;
                    $notification = 'Разработчик: Заявка  №'.$ticket['id'].' принято в работу. Тема:'.$ticket['title'];
                    $res = $this->sendTelegramNotification($notification, 'ShanyrakPlus+');
                }else if ($devStatus == 'toCheck'){
                    $ticket->dev_status = 2;
                    $notification = 'Разработчик: Заявка  №'.$ticket['id'].' отправлено на проверку администратору.Просим проверить.';
                    $res = $this->sendTelegramNotification($notification, 'ShanyrakPlus+');
                }else if($devStatus == 'toClose'){
                    $ticket->dev_status = 3;
                    $notification = 'Администратор: Заявка  №'.$ticket['id'].' закрыто.';
                    $res = $this->sendTelegramNotification($notification, 'ShanyrakPlus+');
                }

                if ($userRole == 'admin') {
                    /*Делаем тикет доступным для разработчиков*/
                    if ($ticket->dev_status == 0){
                        $notification = 'Администратор: Заявка  №'.$ticket['id'].' отправлено на обработку. Тема:'.$ticket['title'];
                        $res = $this->sendTelegramNotification($notification, 'ShanyrakPlus+');
                    }
                    $ticket->is_to_dev = 1;
                }

                if (!$ticket->save()){
                    return json_encode(['status'=>'error', 'error'=>'Ошибка сервера']);
                }
                $messageModel = new Messages();
                $messageModel->time = time();
                $messageModel->user_id = $user['id'];
                $messageModel->ticket_id = $ticket['id'];
                $messageModel->text = $message;
                $messageModel->is_private = 1;
                if(!$messageModel->save()){
                    return json_encode(['status'=>'error', 'error'=>'Ошибка сервера']);
                }
                return json_encode(['status'=>'success', 'error'=>'', 'userRole'=>$userRole]);
            }else{
                return json_encode(['status'=>'error', 'error'=>'Ошибка в запросе']);
            }
        }

    }

    public function actionClose(){
        $tickets = Tickets::find()->where(['<' , 'time', 1622377655])->andWhere(['status'=>2])->all();
        foreach ($tickets as $ticket){
            $ticket->status = 1;
            $ticket->save(false);
            echo date('d.m.y', $ticket->time).'<hr>';
        }
        die;
    }

    public function actionPremium(){

        $actions = Actions::find()->where(['type' => 84])->asArray()->orderBy('id desc')->all();
        foreach ($actions as $action){
            $premium = Premiums::find()->where(['user_id' => $action['user_id']])->one();
            echo $action['user_id'].'<br>'.$action['title'].date('d.m.Y', $action['time']).'<br>'.$premium['tariff_id'];
            echo '<hr>';
        }
        die;


        return true;
    }
    public function actionPv($id){
        $user = User::findOne($id);
        $pv = 0;
        $cv = 0;
        $balans = $user->w_balans;
        $actions = Actions::find()->where(['user_id' => $id])->orderBy('id asc')->all();
        $poponenie = [];
        $matrix = [];
        foreach ($actions as $action){
        }
    }

    public function actionStatistics($id){
        $user = User::findOne($id);
        echo "ID: ".$id;die;
        $pv = 0;
        $cv = 0;
        $balans = $user->w_balans;
        $actions = Actions::find()->where(['user_id' => 12750])->orderBy('id asc')->all();
        foreach($actions as $action)
        {
            echo "User: ".$user."<br>"."Action: ".$action."<br>";
        }
        return $this->render('index', [
            'actions' => $actions,
        ]);
    }

    public function actionSetVer(){
        $vers = Verifications::find()->where(['<' , 'stage', 3])->andWhere(['!=', 'doc1', null])->all();
        foreach ($vers as $ver){
            $ver->stage = 8;
            $ver->save(false);
        }
    }
}
