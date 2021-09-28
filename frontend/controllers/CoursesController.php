<?php
namespace frontend\controllers;

use common\models\Courses;
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
class CoursesController extends Controller
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
                $courses = Courses::find()->where(['is_active'=>1])->orderBy('order asc')->all();
                return $this->render('list',[
                    'courses'=>$courses
                ]);
            }
        }else{
            $course = Courses::findOne($id);
            if(empty($course)){
                return $this->redirect('/site/login');
            }
            return $this->render('index',['id'=>$id,'course'=>$course]);
        }

    }
    public function actionList()
    {
        if(Yii::$app->user->isGuest){
            $this->redirect('/site/login');
        }else{
            $courses = Courses::find()->where(['is_active'=>1])->orderBy('order asc')->all();
            return $this->render('list',[
                'courses'=>$courses
            ]);
        }

    }
    public function actionQuestion()
    {
        if(Yii::$app->user->isGuest){
            $this->redirect('/site/login');
        }
        $model = new QuestionsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
    public function getCustomView($default)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect('/site/login');
        }
        if (isset($this->customViews[$default])) {
            return $this->customViews[$default];
        } else {
            return $default;
        }
    }
    public function getCustomMailView($default)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect('/site/login');
        }
        if (isset($this->customMailViews[$default])) {
            return $this->customMailViews[$default];
        } else {
            return '@frontend/mail/' . $default;
        }
    }
}
