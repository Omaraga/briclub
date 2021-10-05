<?php
namespace frontend\controllers;

use common\models\Referals;
use common\models\UserEmailConfirmToken;
use common\models\User;
use common\models\UserPasswordResetToken;
use frontend\models\PasswordResetRequestForm2;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\widgets\ActiveForm;
use frontend\models\SignupForm;
use yii\web\Response;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function beforeAction($action) {
        if($action->id == 'logout') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'empty',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'empty';
        return $this->render('index');
    }
    public function actionAnti()
    {
        $this->layout = 'empty';
        return $this->render('anti');
    }

    public function actionEdu()
    {
        $this->layout = 'empty';
        return $this->render('edu');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();


        return $this->redirect('/');
    }

    public function actionLogin()
    {
        if(!Yii::$app->user->isGuest){
            $this->redirect('/');
        }
        $this->layout = 'login';
        $model = new \frontend\models\LoginForm();
        if ($model->load(Yii::$app->request->post()) and $model->login()) {
            return $this->redirect(Yii::$app->session->get('returnUrl') ? : '/');
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }



    public function actionSignup()
    {
        if(!Yii::$app->user->isGuest){
            $this->redirect('/');
        }
        $this->layout = 'login';
        $model = new SignupForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect('/');
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Ссылка для восстановления пароля выслана на ваш email!');
                return $this->redirect('/site/login');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $this->layout = 'login';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $userId  = UserPasswordResetToken::findOne([
            'token' => $token
        ])['user_id'];
        $userName = User::findIdentity($userId)->username;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль успешно сохранен!');

            return $this->redirect('/');
        }

        return $this->render('resetPassword', [
            'model' => $model,
            'userName'=>$userName,
        ]);
    }

    public function actionConfirmEmail($token)
    {
        $this->layout = 'login';
        $tokenModel = UserEmailConfirmToken::findToken($token);
        if(!empty($tokenModel)){
            $user = User::findOne($tokenModel['user_id']);
            $user->confirm = 1;
            $user->save();
            $message = Yii::t('users','EMAIL_WAS_CONFIRMED');
            //Yii::$app->getSession()->setFlash('success', Yii::t('users','EMAIL_WAS_CONFIRMED'));
            return $this->render('confirm', [
                'message' => $message,
            ]);
        }else {
            //Yii::$app->getSession()->setFlash('error', Yii::t('users', 'CONFIRMATION_LINK_IS_WRONG'));
            $message = Yii::t('users','CONFIRMATION_LINK_IS_WRONG');
            return $this->render('confirm', [
                'message' => $message,
            ]);
        }

        return $this->goHome();
    }

    public function actionNewUser($token,$course)
    {
        $this->layout = 'login';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }



        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            $user = User::findOne(['password_reset_token'=>$token]);
            $user->confirm = 1;
            $user->save();
            Yii::$app->getUser()->login($user);
            return $this->redirect('/profile');
        }
        return $this->render('setpass', [
            'model' => $model,
        ]);
    }
    public function actionInvite($userName = null, $showStatistic = 0){

        $this->layout = 'invite';
        $userParent = \common\models\User::find()->where(['username'=>$userName])->select('username, fio')->asArray()->one();
        if (!isset($userName) || !isset($userParent)){
            return $this->render('error');
        }
        return $this->render('invite',[
            'userParent' => $userParent,
            ]);

    }
}
