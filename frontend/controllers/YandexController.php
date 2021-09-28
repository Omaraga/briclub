<?php
namespace frontend\controllers;

use common\models\Actions;
use common\models\Courses;
use common\models\Exceptions;
use common\models\MatrixRef;
use common\models\MatrixStart;
use common\models\User;
use common\models\UserLessons;
use common\models\UserPlatforms;
use common\models\Visas;
use YandexCheckout\Model\Notification\NotificationCanceled;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use YandexCheckout\Client;
use YandexCheckout\Model\Notification\NotificationSucceeded;
use YandexCheckout\Model\Notification\NotificationWaitingForCapture;
use YandexCheckout\Model\NotificationEventType;

/**
 * Site controller
 */
class YandexController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['status','success','fail','request'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'request' => ['post'],

                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function actionRequest()
    {
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            $visa = new Visas();
            $visa->user_id = $post['user_id'];
            if(!empty($post['program'])){
                $visa->program = $post['program'];
                $visa->amount = $post['amount'];
            }else{
                $visa->amount_usd = $post['amount'];
                $convert = \common\models\Changes::findOne(2)['cur2'];
                $price_com = $post['amount']*$convert;
                $com = $price_com*0.01;
                $res = $price_com + $com;
                $visa->amount = $res;
            }

            $visa->time = time();
            $visa->save();

            $orderId = $visa['id'];
            $data['orderId'] = "$orderId";
            $data['userId'] = $visa->user_id;
            $data['description'] = $visa->program;
            $data["amount"] = $visa->amount;

            $visa = Visas::findOne($visa['id']);
            $result = null;
            if($visa->status == 3){
                $result = self::createPayment($data);
                $visa->status = 2;
                $visa->save();
            }


            if(!empty($result)){
                /*echo "<pre>";
                var_dump($result);
                exit;*/
                $visa->code = $result["_id"];
                $visa->save();

                return $this->redirect($result["_confirmation"]['_confirmationUrl']);
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте повторить еще раз!'));
                return $this->redirect('/profile');
            }

        }
    }
    function createPayment (array $data){

        $client = new Client();
        $client->setAuth('725868', 'live_x9ZkFEtrioOftZ864LHfEvcMe07IdfFdKe7Vr1ZNH6k');
        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $data['amount'],
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://gcfond.com/yandex/status',
                ),
                'capture' => true,
                'description' => $data['description'],
                'metadata' => ['userId'=>$data['userId']],
            ),
            uniqid('', true)
        );
        return $payment;
    }
    public function actionFail()
    {
        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте повторить еще раз!'));
        return $this->redirect('/profile');
    }
    public function actionStatus()
    {
        /*$client = new Client();
        $paymentId = '26e2f493-000f-5000-a000-17a0f185ec77';
        $client->setAuth('725868', 'live_x9ZkFEtrioOftZ864LHfEvcMe07IdfFdKe7Vr1ZNH6k');
        $payment = $client->getPaymentInfo($paymentId);

        echo "<pre>";
        var_dump($payment->status);
        exit;*/

        /*$ips = ['185.71.76.0/27','185.71.77.0/27','77.75.153.0/25','77.75.154.128/25','2a02:5180:0:1509::/64','2a02:5180:0:2655::/64','2a02:5180:0:1533::/64','2a02:5180:0:2669::/64'];
        if (!in_array($_SERVER["REMOTE_ADDR"],$ips)) {//проверяем ip адрес с которого пришел ответ
            echo "noop";
            die();
        }*/
        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);


        if(!empty($requestBody)){
            if($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED){
                $notification = new NotificationSucceeded($requestBody);
            }elseif ($requestBody['event'] === NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE){
                $notification = new NotificationWaitingForCapture($requestBody);
            }else{
                $notification = new NotificationCanceled($requestBody);
            }

            $payment = $notification->getObject();

            if(!empty($payment)){
                $visa = Visas::find()->where(['code'=>$payment->id,'status'=>2])->one();
                if(!empty($visa)){
                    $visa->status = 1;
                    if($payment->status == 'succeeded'){
                        $visa->status_api = 1;
                        $user = User::findOne($visa['user_id']);
                        $user->w_balans = $user->w_balans + $visa['amount_usd'];
                        $user->save();
                        $action = new Actions();
                        $action->type = 103;
                        $action->status = 1;
                        $action->time = time();
                        $action->sum = $visa['amount_usd'];
                        $action->user_id = $user['id'];
                        $action->title = "Пополнение через yandex";
                        $action->save();
                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Ваш баланс успешно пополнен!'));

                    }elseif($payment->status == 'canceled'){
                        $visa->status_api = 2;
                    }elseif($payment->status == 'waiting_for_capture'){
                        $visa->status_api = 3;
                    }
                    $visa->save();
                }
            }
        }else{
            Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Ваш запрос на пополнение в обработке!'));
            return $this->redirect('/profile');
        }


        /*$order = Visas::findOne($order['id']);
        if(!empty($order)){
            if($order['status_api'] == 1 and $order['status'] == 2){
                $user = User::findOne($order['user_id']);
                $program = $order['program'];
                if(!empty($program)){
                    if($program == 1){
                        $parent = User::findOne($user['parent_id']);
                        if(!empty($parent)){
                            $exc_list_db = Exceptions::find()->all();
                            $exc_list = array();
                            foreach ($exc_list_db as $item) {
                                $exc_list[] = $item['user_id'];
                            }
                            if(!in_array($parent['id'],$exc_list)){
                                if($parent['start'] !=1){
                                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ваш спонсор не активировал Start матрицу!'));
                                    die();
                                }
                            }

                        }
                        if($user['start'] == 1){
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
                            die();
                        }else{
                            $min_balans = 15;
                        }
                    }elseif($program == 2){
                        if($user['newmatrix'] == 1){
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
                            die();
                        }else{
                            $min_balans = 75;
                        }
                    }elseif($program == 3){
                        if($user['global'] == 1){
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
                            die();
                        }else{
                            $min_balans = 75;
                        }
                    }


                    if($program == 1){

                        MatrixStart::plusToRefMatrix($user['id'],1,true,0);
                        Courses::setAccess($user['id'],28);
                        Courses::setAccess($user['id'],29);
                        $user_lesson = new UserLessons();
                        $user_lesson->user_id = $user['id'];
                        $user_lesson->lesson_id = 44;
                        $user_lesson->course_id = 28;
                        $user_lesson->status = 3;
                        $user_lesson->save();
                    }elseif($program == 2){

                        MatrixRef::plusToRefMatrix($user['id'],1,true,0);
                        Courses::setAccess($user['id'],28);
                        Courses::setAccess($user['id'],29);
                        Courses::setAccess($user['id'],30);
                        Courses::setAccess($user['id'],31);
                        Courses::setAccess($user['id'],32);
                        $user_lesson = new UserLessons();
                        $user_lesson->user_id = $user['id'];
                        $user_lesson->lesson_id = 44;
                        $user_lesson->course_id = 28;
                        $user_lesson->status = 3;
                        $user_lesson->save();
                    }elseif($program == 3){

                        UserPlatforms::plusToMatrix($user['id'],1,true);
                        Courses::setAccess($user['id'],28);
                        Courses::setAccess($user['id'],29);
                        Courses::setAccess($user['id'],30);
                        Courses::setAccess($user['id'],31);
                        Courses::setAccess($user['id'],32);
                        $user_lesson = new UserLessons();
                        $user_lesson->user_id = $user['id'];
                        $user_lesson->lesson_id = 44;
                        $user_lesson->course_id = 28;
                        $user_lesson->status = 3;
                        $user_lesson->save();
                    }



                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Поздравляем! Для вас открыт доступ к курсам!'));

                }else{

                    $user->w_balans = $user->w_balans + $order['amount_usd'];
                    $user->save();
                    $action = new Actions();
                    $action->type = 102;
                    $action->status = 1;
                    $action->time = time();
                    $action->sum = $order['amount_usd'];
                    $action->user_id = $user['id'];
                    $action->title = "Пополнение через карту";
                    $action->save();
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Ваш баланс успешно пополнен!'));

                }
                $order->status = 1;
                $order->save();
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
            return $this->redirect('/profile');
        }*/

    }
}
