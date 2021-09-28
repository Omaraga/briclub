<?php
namespace frontend\controllers;

use common\models\Actions;
use common\models\EventTicketTypes;
use common\models\Pretrans;
use common\models\Referals;
use common\models\TokenBonuses;
use common\models\TokenNodes;
use common\models\TokenNodesQueries;
use common\models\TokenPretrans;
use common\models\Tokens;
use common\models\TokensActions;
use common\models\UserEmailConfirmToken;
use common\models\User;
use common\models\UserPasswordResetToken;
use frontend\models\forms\GetTokenForm;
use frontend\models\forms\NodeQueryForm;
use frontend\models\forms\TokenTransfersForm;
use frontend\models\PasswordResetRequestForm2;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidParamException;
use yii\data\Pagination;
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
class TokensController extends Controller
{
    //public $layout = 'wallet/wallet';

    public function actionIndex()
    {
        return $this->render('index');
    }
    /*public function actionNodeInfo()
    {
        $user = Yii::$app->user->identity;
        $user_node = TokenNodes::findOne(['user_id'=>$user['id']]);
        if(empty($user_node)){
            return $this->redirect('/tokens');
        }else{

        }

        return $this->render('node-info');
    }*/
    public function actionGet()
    {
        $ref = null;
        $get = Yii::$app->request->get();
        $ref_cookie = null;
        $promoCode = null;
        if(!empty($get['promo'])){
            $promoCode = $get['promo'];
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'promo',
                'value' => $get['promo'],
            ]));
        }
        $price = null;
        $tokens = null;
        $parent = null;
        $program = 5;
        if(!empty($get['ticket'])){
            $ticket = EventTicketTypes::findOne($get['ticket']);
            $course = \common\models\Changes::findOne(3)['cur2'];
            $price = $ticket['price']*$course;
            $tokens = $ticket['price'];
            $program = 10;
        }

        if(Yii::$app->user->isGuest){
            Yii::$app->session->set("returnUrl", '/tokens/get');
            return $this->redirect('/site/login');
        }
        /*if(Yii::$app->user->id !=12864){
            $this->redirect('/profile');
        }*/
        $model = new GetTokenForm();
        if($model->load(Yii::$app->request->post()) and $model->validate()){

            if(!empty(Yii::$app->session->get("isSend")) ){
                Yii::$app->session->remove("isSend");
                return $this->redirect('/profile');
            }
            else{
                Yii::$app->session->set("isSend", true);
                //Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! Повторите еще раз позже'));
            }

            $course = \common\models\Changes::findOne(3)['cur2'];
            $bonus = 0;
			if(!empty($model->parent)){
                $promoCode = $model->parent;
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'promo',
                    'value' => $model->parent,
                ]));
            }
            $parent = $model->parent;
            $sum = $model->sum;
            $price = $sum;
            $tokens = round(($price/$course), 8);
            return $this->render('get',[
                'model'=>$model,
                'price'=>$price,
                'tokens'=>$tokens,
                'promo'=>$promoCode,
                'program'=>$program,
            ]);

        }
        else{
            Yii::$app->session->remove("isSend");
        }
        return $this->render('get',[
            'model'=>$model,
            'price'=>$price,
            'tokens'=>$tokens,
            'promo'=>$promoCode,
            'program'=>$program,
        ]);
    }

    public function actionTokenTransfer()
    {
        $model = new TokenTransfersForm();
        $commission_percent = 0.3;

        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->user->identity['block'] == 1){
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Переводы временно приостановлены для вашего аккаунта'));

            return $this->redirect('/profile');
        }
        $error = false;
        $success = false;
        $code = new \frontend\models\forms\CodeForm();
        $user = User::findOne(Yii::$app->user->identity['id']);
        $user_tokens = Tokens::findOne(['user_id' => $user->id, 'wallet_type' => 7]);
        $pretrans = TokenPretrans::find()->where(['user_id'=>$user['id'],'status'=>3])->one();

        if($code->load(Yii::$app->request->post()) and $code->validate()){
            if(!empty(Yii::$app->session->get("isSend")) ){
                Yii::$app->session->remove("isSend");
                return $this->redirect('/profile');
            }
            else{
                Yii::$app->session->set("isSend", true);
            }
            if(!empty($pretrans)){
                if($pretrans['code'] == $code['code']){
                    $user2 = User::findOne($pretrans->user2_id);
                    $user2_tokens = Tokens::findOne(['user_id' => $user2->id, 'wallet_type' => 7]);
					if(empty($user2_tokens)){
						$user2_tokens = new Tokens();
						$user2_tokens->user_id = $pretrans->user2_id;
						$user2_tokens->save();
					}

                    $user_tokens['balans'] = $user_tokens->balans - $pretrans->sum - $pretrans->fee;
                    $user2_tokens['balans'] = $user2_tokens->balans + $pretrans->sum;

                    $user_tokens->save();
                    $user2_tokens->save();

                    $action2 = new Actions();
                    $action2->type = 61;
                    $action2->title = "Списание комиссии за перевод GRC ";
                    $action2->user_id = Yii::$app->user->id;
                    $action2->time = time();
                    $action2->status = 1;
                    $action2->tokens = $pretrans->fee;
                    $action2->save();

                    $action = new Actions();
                    $action->type = 57;
                    $action->title = "Вам поступил перевод токенов от пользовтеля ".$user['username'].". Комиссия: ".$pretrans->fee;;
                    $action->user_id = $user2['id'];
                    $action->user2_id = $user['id'];
                    $action->time = time();
                    $action->status = 1;
                    $action->sum = 0;
                    $action->tokens = $pretrans->sum;
					$action->fee = $pretrans->fee;
                    $action->save();

                    $action2 = new Actions();
                    $action2->type = 56;
                    $action2->title = "Вы перевели токены пользователю ".$user2['username'].". Комиссия: ".$pretrans->fee;
                    $action2->user_id = $user['id'];
                    $action2->user2_id = $user2['id'];
                    $action2->time = time();
                    $action2->status = 1;
                    $action2->sum = 0;
                    $action2->tokens = $pretrans->sum;
					$action2->fee = $pretrans->fee;
                    $action2->save();

                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Перевод успешно выполнен!'));

                    $this->redirect('/profile');
                }else{
                    $pretrans->delete();
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Неверный код активации!'));
                    return $this->redirect('/profile/transfer');
                }
            }
        }else{
            Yii::$app->session->remove("isSend");
            if(!empty($pretrans)){
                $pretrans->delete();
                $pretrans = null;
            }
        }
        if($model->load(Yii::$app->request->post()) and $model->validate()){

            $user = User::findOne(Yii::$app->user->identity['id']);
            $tokens = Tokens::findOne(['user_id' => $user->id]);
			$fee = $model->sum * ($commission_percent / 100);
            
            if($tokens['balans']>=($model->sum)*1){
                $pretrans = new TokenPretrans();
                $pretrans->user_id = $user['id'];
                $pretrans->sum = $model->sum;
				$pretrans->fee = $fee;
                $pretrans->user2_id = User::find()->where(['username'=>$model->username])->one()['id'];
                $pretrans->time = time();

                $pretrans->code = rand(1000,999999);
                $pretrans->save();

                Pretrans::sendEmail($user['id'],$pretrans->code);

            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка! У Вас недостаточно токенов!'));
            }

        }
        return $this->render('token-transfer',[
            'model' => $model,
            'error' => $error,
            'success' => $success,
            'code' => $code,
            'pretrans' => $pretrans,
        ]);
    }

    public function actionNodeQuery(){
        $model = new NodeQueryForm();

        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->user->identity['block'] == 1){
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Переводы временно приостановлены для вашего аккаунта'));

            return $this->redirect('/profile');
        }

        if($model->load(Yii::$app->request->post()) and $model->validate()){
            $sum = $model->sum;
            $user_id = Yii::$app->user->id;
            $tokens = Tokens::findOne(['user_id' => $user_id]);
            if($sum >= 1000){
                $tokens->balans -= $sum;
                $tokens->save();

                $nodeQuery = new TokenNodesQueries();
                $nodeQuery->user_id = $user_id;
                $nodeQuery->tokens_count = $sum;
                $nodeQuery->query_date = time();
                $nodeQuery->status = 3;
                $nodeQuery->save();

                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вы успешно отправили заявку чтобы стать нодой'));
                return $this->redirect('/');
            }
            else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'На Вашем аккаунте недостаточно токенов для того, чтобы стать нодой'));
                return $this->redirect('/');
            }
        }

        return $this->render('node-query', [
            'model' => $model
        ]);
    }

    public function actionAllTransactions(){
        $actions = \common\models\Actions::find()->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $actions;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $actions->offset($pages->offset)
            ->limit(10)
            ->all();

        return $this->render('all-transactions', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }


    public function actionNodeTransactions(){
        $node = TokenNodes::findOne(['user_id' => Yii::$app->user->id]);
        if($node != null){
            $actions = \common\models\Actions::find()->where(['node_id' => $node->id])->orderBy(['id' => SORT_DESC]);
            $countQuery = clone $actions;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $models = $actions->offset($pages->offset)
                ->limit(10)
                ->all();
        }
        else{
            $actions = Actions::find()->limit(0);
            $countQuery = clone $actions;
            $pages = new Pagination(['totalCount' => 0]);
            $models = $actions->offset($pages->offset)
                ->limit(0)
                ->all();
        }

        return $this->render('node-transactions', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }
}
