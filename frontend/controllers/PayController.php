<?php
namespace frontend\controllers;

use common\models\Actions;
use common\models\Acts;
use common\models\Courses;
use common\models\EventTickets;
use common\models\EventTicketTypes;
use common\models\Exceptions;
use common\models\logic\PremiumsManager;
use common\models\MatrixRef;
use common\models\MatrixStart;
use common\models\MLevels;
use common\models\Premiums;
use common\models\PremiumTariffs;
use common\models\PromoCodes;
use common\models\ShanyrakInfo;
use common\models\ShanyrakUser;
use common\models\Tokens;
use common\models\UserEmailConfirmToken;
use common\models\User;
use common\models\UserLessons;
use common\models\UserPasswordResetToken;
use common\models\UserPlatforms;
use common\models\Visas;
use frontend\models\PasswordResetRequestForm2;
use kartik\mpdf\Pdf;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class PayController extends Controller
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
                        'actions' => ['success','fail','request'],
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
                $visa->amount = $post['amount']*100;
                $visa->amount_usd = $post['amount_usd'];
                if($post['program']==5 or $post['program']==10){
                    $visa->tokens = $post['tokens'];
                    $visa->promo = $post['promo'];
                }
                if($post['program'] == 14){
                    $visa->premium_id = $post['premium_id'];
                }
            }else{
                $visa->amount_usd = $post['amount'];
                $convert = \common\models\Changes::findOne(1)['cur2'];
                $price_com = $post['amount']*$convert;
                $com = $price_com*0.01;
                $res = $price_com + $com;
                $visa->amount = $res*100;
            }

            $visa->time = time();
            $visa->save();

            /*echo "<pre>";
            var_dump($visa);
            exit;*/
            $orderId = $visa['id'];
            $data["MerchId"] = "11806317384139061";
            $data["login"] = "11806317384139061";
            $data["pass"] = "H4D8Wsb7Q2P2WdB6UXTJ";
            $data["demo"] = false;
            if($visa->user_id == 12864 || $visa->user_id == 13636){
                $data["demo"] = true;
            }
            $data["callback"] = "https://lseplatform.com/pay/success";
            $data['orderId'] = "$orderId";
            $data['description'] = $visa->program;
            $data['returnUrl'] = "https://lseplatform.com";
            $data["amount"] = intval($visa->amount);

            $visa = Visas::findOne($visa['id']);
            $result = null;
            if($visa->status == 3){
                $result = self::createPayment($data);
                $visa->status = 2;
                $visa->save();
            }

            if(!empty($result)){
                if(isset($result['id'])){
                    $visa->code = $result["id"];
                }else{
                    $visa->code = $result->id;
                }

                $visa->save();

                return $this->redirect($result["url"]);
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте повторить еще раз!'));
                return $this->redirect('/profile');
            }

        }
    }
    function createPayment (array $data){

        if($data["amount"]<=0){
            throw new Exception('цена не указана или меньше 0');
        }
        $dataArray=array(
            "merchantId"=>      $data["MerchId"],
            "callbackUrl"=>     $data["callback"],
            "orderId"   =>      $data['orderId'],
            "description"=>     $data['description'],
            "demo"      =>      $data['demo'],
            "returnUrl" =>      $data['returnUrl'],
            "amount"  =>        $data["amount"]
        );

        if (isset($data['email'])|| isset($data['phone'])){
            $dataArray['customerData']=array(
                "email"     =>      isset($data['email'])?$data['email']:"",
                "phone"     =>      isset($data['phone'])?$data['phone']:""
            );
        }
        if (isset($data['metadata'])){
            $dataArray["metadata"]=$data['metadata'];
        }

        $data_string = json_encode ($dataArray, JSON_UNESCAPED_UNICODE);
        $curl = curl_init( "https://ecommerce.pult24.kz/payment/create");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($data["login"].':'.$data["pass"]),
            'Content-Length: ' . strlen($data_string)
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        curl_close($curl);
        $result=json_decode($result,true);
        return $result;
    }
    public function actionFail()
    {
        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте повторить еще раз!'));
        return $this->redirect('/profile');
    }
    public function actionSuccess()
    {

        if ($_SERVER["REMOTE_ADDR"]!="35.157.105.64") {//проверяем ip адрес с которого пришел ответ
            echo "noop";
            die();
        }
        $json = json_decode (file_get_contents('php://input'));
        $out=true;
        if ($json->status==1){
            $order = Visas::findOne($json->orderId);
            $order->status_api = 1;
            $order->save();
        }else{
            $order = Visas::findOne($json->orderId);
            $order->status_api = 2;
            $order->save();
        }
        header( 'HTTP/1.1 200 OK' );
        if(gettype($out)=="boolean"){
            echo '{"accepted":'.(($out) ? 'true' : 'false').'}';
        }else{
            throw  new  Exception($out);
        }

        $order = Visas::findOne($order['id']);
        if(!empty($order)){
            if($order['status_api'] == 1 and $order['status'] == 2){
                $user = User::findOne($order['user_id']);
                $program = $order['program'];
                if(!empty($program)){
                    if($program == 2){
                        if($user['newmatrix'] == 1){
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
                            die();
                        }else{
                            $min_balans = 103;
                        }
                    }


                    if($program == 2){

                        MatrixRef::plusToRefMatrix($user['id'],1,true,0,true,null,1);
                        $action = new Actions();
                        $action->type = 122;
                        $action->status = 1;
                        $action->time = time();
                        /*$action->sum = $order['amount_usd'];*/
                        $action->sum = 103;
                        $action->user_id = $user['id'];
                        $action->title = "Активация через карту";
                        $action->comment = "Активация через VISA/MASTERCARD";
                        $action->save();
                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Поздравляем! Для вас открыт доступ к библиотеке!'));
                    }
                    if($program == 5){
                        $sum = $order['amount_usd'];
                        $course = \common\models\Changes::findOne(3)['cur2'];
                        $tokens = $order['tokens'];
                        $token = Tokens::findOne(['user_id'=>$user['id']]);
                        if(empty($token)){
                            $token = new Tokens();
                            $token->user_id = $user['id'];
                            $token->balans = $tokens;
                        }else{
                            $token->balans = $token->balans + $tokens;
                        }

                        $token->save();

                        if(!empty($order['promo'])){
                            $sourceUser = User::findOne(['username' => $order['promo']]);
                            if($sourceUser != null){
                                $tokenBonus = $tokens * 0.025;
                                $usBonus = $order->amount_usd * 0.025;
                                $sourceUser['w_balans'] += $usBonus;
                                $sourceUser->save();

                                $actionBonus = new Actions();
                                $actionBonus->type = 72;
                                $actionBonus->title = "Бонус в US за продажу токенов в количестве " . $usBonus;
                                $actionBonus->user_id = $sourceUser['id'];
                                $actionBonus->time = time();
                                $actionBonus->status = 1;
                                $actionBonus->sum = $usBonus;
                                $actionBonus->promo = $order['promo'];
                                $actionBonus->content = "Visa/Mastercard";
                                $actionBonus->system = 2;
                                $actionBonus->title = "Бонус в US за продажу токенов в количестве " . $usBonus;
                                $actionBonus->save();

                                $bonusTokens = Tokens::findOne(['user_id' => $sourceUser->id, 'wallet_type' => 8]);

                                if($bonusTokens == null){
                                    $bonusTokens = new Tokens();
                                    $bonusTokens->user_id = $sourceUser->id;
                                }
                                $bonusTokens->balans += $tokenBonus;
                                $bonusTokens->save();

                                $actionBonusTokens = new Actions();
                                $actionBonusTokens->type = 73;
                                $actionBonusTokens->title = "Бонус в GRC за покупку токенов в количестве " . $tokenBonus;
                                $actionBonusTokens->user_id = $sourceUser['id'];
                                $actionBonusTokens->time = time();
                                $actionBonusTokens->status = 1;
                                $actionBonusTokens->tokens = $tokenBonus;
                                $actionBonusTokens->promo = $order['promo'];
                                $actionBonusTokens->content = "Visa/Mastercard";
                                $actionBonusTokens->system = 2;
                                $actionBonusTokens->title = "Бонус в GRC за покупку токенов в количестве " . $tokenBonus;
                                $actionBonusTokens->save();
								
								$promoCode = new PromoCodes();
                                $promoCode->user_id = $sourceUser['id'];
                                $promoCode->promo = $order['promo'];
                                $promoCode->created_at = time();
                                $promoCode->save();
                            }
                        }

                        $action = new Actions();
                        $action->type = 55;
                        $action->title = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен. Через Visa/Mastercard";
                        $action->user_id = $user['id'];
                        $action->time = time();
                        $action->status = 1;
                        $action->sum = $sum;
                        $action->promo = $order['promo'];
                        $action->content = "Visa/Mastercard";
                        $action->system = 2;
                        $action->tokens = $tokens;
                        $action->comment = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен";
                        $action->save();

                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вы успешно приобрели токены!'));
                    }
                    if($program == 10){
                        $sum = $order['amount_usd'];
                        $course = \common\models\Changes::findOne(3)['cur2'];
                        $tokens = $order['tokens'];
                        $token = Tokens::findOne(['user_id'=>$user['id']]);
                        if(empty($token)){
                            $token = new Tokens();
                            $token->user_id = $user['id'];
                            $token->balans = $tokens;
                        }else{
                            $token->balans = $token->balans + $tokens;
                        }

                        $token->save();

                        $action = new Actions();
                        $action->type = 55;
                        $action->title = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен. Через Visa/Mastercard";
                        $action->user_id = $user['id'];
                        $action->time = time();
                        $action->status = 1;
                        $action->sum = $sum;
                        $action->promo = $order['promo'];
                        $action->content = "Visa/Mastercard";
                        $action->system = 2;
                        $action->tokens = $tokens;
                        $action->comment = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен";
                        $action->save();

                        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вы успешно приобрели токены!'));
                        $ticket = null;
                        $user_id = $action->user_id;
                        if($action->tokens>=15 and $action->tokens<45){
                            $background = 'ticket4';
                            $ticket = new EventTickets();
                            $ticket->user_id = $user_id;
                            $ticket->event_id = 1;
                            $ticket->type_id = 4;
                            $ticket->time = time();
                            $ticket->status = 1;
                            $ticket->save();

                            $ticket_type = EventTicketTypes::findOne(4);
                            $ticket_type->count = $ticket_type->count - 1;
                            $ticket_type->save();
                        }
                        if($action->tokens>=45  and $action->tokens<65){
                            $background = 'ticket3';
                            $ticket = new EventTickets();
                            $ticket->user_id = $user_id;
                            $ticket->event_id = 1;
                            $ticket->type_id = 3;
                            $ticket->time = time();
                            $ticket->status = 1;
                            $ticket->save();

                            $ticket_type = EventTicketTypes::findOne(3);
                            $ticket_type->count = $ticket_type->count - 1;
                            $ticket_type->save();

                        }
                        if($action->tokens>=65  and $action->tokens<115){
                            $background = 'ticket2';
                            $ticket = new EventTickets();
                            $ticket->user_id = $user_id;
                            $ticket->event_id = 1;
                            $ticket->type_id = 2;
                            $ticket->time = time();
                            $ticket->status = 1;
                            $ticket->save();
                            $ticket_type = EventTicketTypes::findOne(2);
                            $ticket_type->count = $ticket_type->count - 1;
                            $ticket_type->save();

                        }
                        if($action->tokens>=115){
                            $background = 'ticket1';
                            $ticket = new EventTickets();
                            $ticket->user_id = $user_id;
                            $ticket->event_id = 1;
                            $ticket->type_id = 1;
                            $ticket->time = time();
                            $ticket->status = 1;
                            $ticket->save();

                            $ticket_type = EventTicketTypes::findOne(1);
                            $ticket_type->count = $ticket_type->count - 1;
                            $ticket_type->save();

                        }

                        if(!empty($ticket)){
                            $content = $this->renderPartial('/events/cert',[
                                'id'=>$ticket['id'],
                                'name'=>$user['username']
                            ]);

                            $pdf = new Pdf([
                                // set to use core fonts only
                                'mode' => Pdf::MODE_CORE,
                                // A4 paper format
                                'format' => Pdf::FORMAT_A4,
                                // portrait orientation
                                'orientation' => Pdf::ORIENT_LANDSCAPE,
                                // stream to browser inline
                                'destination' => Pdf::DEST_BROWSER,
                                // your html content input
                                'content' => $content,
                                'cssFile' => '@frontend/web/css/cert.css',
                                'mode' => Pdf::MODE_UTF8,
                                'marginLeft' =>0,
                                'marginRight' =>0,
                                'marginTop' =>0,
                                'marginBottom' =>0,
                            ]);
                            //return $pdf->render();
                            $rand = rand(900000,9000000);
                            $dir = Yii::getAlias('@frontend/web/tickets/');
                            $fileName = $rand . '.pdf';
                            $mpdf = $pdf->api;
                            $stylesheet = "
                                    .cr-content{
                                        overflow: hidden;
                                        height: 100%;
                                        width: 100%;
                                        background: url(\"/img/".$background.".jpg\") no-repeat;
                                        background-size: contain;
                                        background-position: center;
                                        position: relative;
                                    }
                                    .cr-name{
                                        
                                        padding-left: 980px!important;
                                        font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                                        font-weight: lighter;
                                        color: #fff;
                                        display: block;
                                        font-size: 16px;
                                    }
                                    .cr-id{
                                        padding-top: 230px!important;
                                        padding-left: 540px!important;
                                        text-align: center;
                                        font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                                        font-weight: lighter;
                                        color: #fff;
                                        display: block;
                                        font-size: 36px;
                                    }
                                    .cr-id2{
                                        padding-top: 300px!important;
                                        padding-left: 1000px!important;
                                        font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                                        font-weight: lighter;
                                        color: #fff;
                                        display: block;
                                        font-size: 36px;
                                    }
                                    
                                ";
                            $mpdf->WriteHtml($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
                            $mpdf->WriteHtml($content);
                            $mpdf->Output($dir . $fileName); // call the mpdf api output as needed

                            $link = '/tickets/'. $fileName;
                            $ticket->link = $link;
                            $ticket->save();

                            $action = new Actions();
                            $action->type = 300;
                            $action->title = "Покупка билета на мероприятие";
                            $action->user_id = $user_id;
                            $action->time = time();
                            $action->status = 1;
                            $action->content = $link;
                            $action->tokens = $tokens;
                            $action->comment = "Покупка билета на мероприятие";
                            $action->save();

                            Acts::sendEmail($user['id'],$link,1);
                        }
                    }


                    if($program == 14){
//                        $premium = Premiums::findOne(['user_id' => $user]);
//                        if($premium == null){
//                            $premium = new Premiums();
//                        }
//                        $premium->time = time();
//                        $premium->user_id = $user->id;
//                        $premium->is_active = true;
//
//                        $tariffs = PremiumTariffs::findOne([$order['premium_id']]);
//
//                        $premium->expires_at = $tariffs->time;
//                        $premium->tariff_id = $tariffs->id;
//
//                        $premium->save(false);

                        $tariff_id = $order['id'];

                        $status = PremiumsManager::addPremium($tariff_id, $user->id);

                        if($status == 1){
                            $action = new Actions();
                            $action->user_id = $user->id;
                            $action->time = time();
                            $action->type = 80;
                            $action->status = 1;
                            $action->title = "Вы успешно активировали Premium-аккаунт";
                            $action->save();
                        }
                    }

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
        }

    }
}
