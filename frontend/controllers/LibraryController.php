<?php
namespace frontend\controllers;

use common\models\Audios;
use common\models\Actions;
use yii\helpers\ArrayHelper;
use common\models\Courses;
use common\models\Library;
use common\models\User;
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
class LibraryController extends Controller
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
                $books = Library::find()->where(['status'=>1])->orderBy('order asc')->all();
                return $this->render('list',[
                    'books'=>$books
                ]);
            }
        }else{
            $book = Library::findOne($id);
            $audios = Audios::find()->where(['lib_id' => $id])->all();
            if(empty($book)){
                return $this->redirect('/library');
            }
            return $this->render('index',['id'=>$id,'book'=>$book, 'audios' => $audios]);
        }

    }



}
