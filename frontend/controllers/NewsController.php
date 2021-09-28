<?php
namespace frontend\controllers;

use common\models\Courses;
use common\models\Library;
use common\models\News;
use frontend\models\QuestionsForm;
use Yii;
use yii\base\InvalidArgumentException;
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
class NewsController extends Controller
{
    public $customViews = [];

    public $customMailViews = [];
    public function actionIndex($id=null)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect('/site/login');
        }
        if(empty($id)){
            if(Yii::$app->user->isGuest){
                return $this->redirect('/site/login');
            }else{
                $news = News::find()->where(['status'=>1])->orderBy('id desc')->all();
                return $this->render('list',[
                    'news'=>$news
                ]);
            }
        }else{
            $news = News::findOne($id);
            if(empty($news)){
                return $this->redirect('/site/login');
            }
            return $this->render('index',['id'=>$id,'news'=>$news]);
        }

    }

}
