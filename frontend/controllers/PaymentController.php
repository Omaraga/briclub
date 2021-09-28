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
class PaymentController extends Controller
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
                        'actions' => ['success','status','fail','activ','request','test'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'status' => ['post'],
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
    public function actionTest(){
        $user_id = 123;
        $type_id = 4;
        $tokens = 15;//45//65//115
        $user = User::findOne($user_id);
        $background = 'ticket'.$type_id;
        $ticket = new EventTickets();
        $ticket->user_id = $user_id;
        $ticket->event_id = 1;
        $ticket->type_id = $type_id;
        $ticket->time = time();
        $ticket->status = 1;
        $ticket->save();

        $ticket_type = EventTicketTypes::findOne($type_id);
        $ticket_type->count = $ticket_type->count - 1;
        $ticket_type->save();

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
    public function actionRequest()
    {
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            $visa = new Visas();
            $visa->user_id = $post['user_id'];
            if(!empty($post['program'])){
                $visa->program = $post['program'];
                $visa->amount = $post['amount'];
                if($post['program'] == 14){
                    $visa->premium_id = $post['premium_id'];
                }
            }else{
                $visa->amount_usd = $post['amount'];
            }

            $visa->time = time();
            $visa->save();
            /*$client = new \yii\httpclient\Client();
            $response = $client->createRequest()
                ->setMethod('post')
                ->setUrl('https://perfectmoney.com/api/step1.asp')
                ->setData([
                    'PAYEE_ACCOUNT'=>'U24987903',
                    'PAYEE_NAME'=>"Gcfond.com",
                    'PAYMENT_UNITS'=>"USD",
                    'PAYMENT_ID'=>$visa['id'],
                    'PAYMENT_AMOUNT'=>$visa['amount'],
                    'STATUS_URL'=>"http://gcfond2.loc/payment/status",
                    'PAYMENT_URL'=>"http://gcfond2.loc/payment/activ",
                    'NOPAYMENT_URL'=>"http://gcfond2.loc/payment/fail",
                ])
                ->send();*/

            return $this->redirect(['https://perfectmoney.com/api/step1.asp'], [

                'data'=>[

                    'method' => 'post',

                    'params'=>[
                        'PAYEE_ACCOUNT'=>'U24987903',
                        'PAYEE_NAME'=>"Gcfond.com",
                        'PAYMENT_UNITS'=>"USD",
                        'PAYMENT_ID'=>$visa['id'],
                        'PAYMENT_AMOUNT'=>$visa['amount'],
                        'STATUS_URL'=>"http://gcfond2.loc/payment/status",
                        'PAYMENT_URL'=>"http://gcfond2.loc/payment/activ",
                        'NOPAYMENT_URL'=>"http://gcfond2.loc/payment/fail",
                    ],

                ]

            ]);
            echo "<pre>";
            var_dump($response->content);
            exit;
            $visa->code = $result["orderId"];
            $visa->save();
            return $this->redirect($result["formUrl"]);

        }
    }
    public function actionFail()
    {
        Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте повторить еще раз!'));
        $this->redirect('/');
    }

    public function actionActiv()
    {
        /*$get = Yii::$app->request->get();

        $user_id = $get['PAYMENT_ID'];

        $user_ar = explode('-',$user_id);
        $program = null;
        if(isset($user_ar[1])){
            if(!empty($user_ar[1])){
                $program = $user_ar[1];
            }
        }*/
        /*if(!empty($program)){*/
        Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Оплата прошла успешно!'));
        return $this->redirect('/');
        //}


    }
    public function actionStatus()
    {
        $this->enableCsrfValidation = false;
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();


            $secret = strtoupper(md5('0MgQ06uG8b4VBFWnTsVFbdJWF'));
            $hash = $post['PAYMENT_ID'].":".
                $post['PAYEE_ACCOUNT'].":".
                $post['PAYMENT_AMOUNT'].":".
                $post['PAYMENT_UNITS'].":".
                $post['PAYMENT_BATCH_NUM'].":".
                $post['PAYER_ACCOUNT'].":".
                $secret.":".
                $post['TIMESTAMPGMT'];
            $hash = strtoupper(md5($hash));
            if($post['V2_HASH'] == $hash){
                $visa = new Visas();
                $course = \common\models\Changes::findOne(3)['cur2'];
                $payment_id = $post['PAYMENT_ID'];
                $visa->code = $post['PAYMENT_ID'];
                $visa->amount = $post['PAYMENT_AMOUNT'];
                $user_ar = explode('-',$payment_id);
                $user_id = $user_ar[0];
                if(isset($user_ar[1])){
                    if(!empty($user_ar[1])){
                        $visa->program = $user_ar[1];
                    }
                }
                if(isset($user_ar[2])){
                    if(!empty($user_ar[2])){
                        $visa->promo = $user_ar[2];
                    }
                }
                $visa->user_id = $user_id;
                $visa->amount_usd = $post['PAYMENT_AMOUNT'];
                $visa->time = time();
                $visa->tokens = $visa->amount_usd/$course;
                $visa->save();

                $user = User::findOne($visa->user_id);
                if(!empty($visa->program)){
                    $program = $visa->program;
                    if($program == 2){

                        if($user['newmatrix'] !=1){
                            MatrixRef::plusToRefMatrix($user['id'],1,true,0,true,null,1);
                            $action = new Actions();
                            $action->type = 66;
                            $action->status = 1;
                            $action->time = time();
                            $action->sum = $visa->amount_usd;
                            $action->user_id = $user['id'];
                            $action->title = "Активация через Perfect Money";
                            $action->save();
                            Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Поздравляем! Для вас открыт доступ к библиотеке!'));
                        }
                    }
                    if($program == 5){
                        $sum = $visa->amount_usd;

                        $tokens = $visa->tokens;
                        $token = Tokens::findOne(['user_id'=>$user_id]);
                        if(empty($token)){
                            $token = new Tokens();
                            $token->user_id = $user_id;
                            $token->balans = $tokens;
                        }else{
                            $token->balans = $token->balans + $tokens;
                        }

                        $token->save();

                        if(!empty($visa->promo)){
                            $sourceUser = User::findOne(['username' => $visa->promo]);
                            if($sourceUser != null){
                                $tokenBonus = $tokens * 0.025;
                                $usBonus = $visa->amount_usd * 0.025;
                                $sourceUser['w_balans'] += $usBonus;
                                $sourceUser->save();

                                $actionBonus = new Actions();
                                $actionBonus->type = 72;
                                $actionBonus->title = "Бонус в US за продажу токенов в количестве " . $usBonus;
                                $actionBonus->user_id = $sourceUser['id'];
                                $actionBonus->time = time();
                                $actionBonus->status = 1;
                                $actionBonus->sum = $usBonus;
                                $actionBonus->promo = $visa->promo;
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
                                $actionBonusTokens->promo = $visa->promo;
                                $actionBonusTokens->content = "Visa/Mastercard";
                                $actionBonusTokens->system = 2;
                                $actionBonusTokens->title = "Бонус в GRC за покупку токенов в количестве " . $tokenBonus;
                                $actionBonusTokens->save();
								
								$promoCode = new PromoCodes();
                                $promoCode->user_id = $sourceUser['id'];
                                $promoCode->promo = $visa->promo;
                                $promoCode->created_at = time();
                                $promoCode->save();
                            }
                        }

                        $action = new Actions();
                        if(!empty($visa->promo)){
                            $action->promo = $visa->promo;
                        }
                        $action->type = 55;
                        $action->title = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен. Через PerfectMoney";
                        $action->user_id = $user_id;
                        $action->time = time();
                        $action->status = 1;
                        $action->sum = $sum;
                        $action->content = "PerfectMoney";
                        $action->system = 1;
                        $action->tokens = $tokens;
                        $action->comment = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен.";
                        $action->save();

                    }
                    if($program == 10){
                        $sum = $visa->amount_usd;

                        $tokens = $visa->tokens;
                        $token = Tokens::findOne(['user_id'=>$user_id]);
                        if(empty($token)){
                            $token = new Tokens();
                            $token->user_id = $user_id;
                            $token->balans = $tokens;
                        }else{
                            $token->balans = $token->balans + $tokens;
                        }

                        $token->save();

                        $action = new Actions();
                        if(!empty($visa->promo)){
                            $action->promo = $visa->promo;
                        }
                        $action->type = 55;
                        $action->title = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен. Через PerfectMoney";
                        $action->user_id = $user_id;
                        $action->time = time();
                        $action->status = 1;
                        $action->sum = $sum;
                        $action->content = "PerfectMoney";
                        $action->system = 1;
                        $action->tokens = $tokens;
                        $action->comment = "Покупка токенов в количестве ".$tokens." по курсу $".$course." за 1 токен.";
                        $action->save();


                        $ticket = null;
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
//                        $premium = new Premiums();
//                        $premium->time = time();
//                        $premium->user_id = $user->id;
//                        $premium->is_active = true;
//
//                        $tariffs = PremiumTariffs::findOne([$visa['premium_id']]);
//
//                        $premium->expires_at = $tariffs->time;
//                        $premium->tariff_id = $tariffs->id;
//
//                        $premium->save();

                        $tariff_id = $visa['id'];

                        $status = PremiumsManager::addPremium($tariff_id, $user->id);

                        if($status == 1){
                            $action = new Actions();
                            $action->user_id = $user->id;
                            $action->time = time();
                            $action->type = 82;
                            $action->status = 1;
                            $action->title = "Вы успешно активировали Premium-аккаунт";
                            $action->save();
                        }
                    }
                }else{
                    $user->w_balans = $user->w_balans + $visa->amount_usd;
                    $user->save();
                    $action = new Actions();
                    $action->type = 6;
                    $action->status = 1;
                    $action->time = time();
                    $action->sum = $visa->amount_usd;
                    $action->user_id = $user['id'];
                    $action->title = "Пополнение через Perfect Money";
                    $action->save();
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Ваш баланс успешно пополнен!'));
                }
            }


        }
    }
}
