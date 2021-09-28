<?php

namespace frontend\modules\academy\controllers;

use common\models\CourseReviews;
use common\models\User;
use frontend\modules\academy\components\BaseAcademyController;
use frontend\modules\academy\models\forms\RekassaForm;
use Yii;
use common\models\Courses;
use yii\helpers\Url;
use yii\web\UploadedFile;
/**
 * Default controller for the `academy` module
 */
class DefaultController extends BaseAcademyController
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        //Добавили временно потом надо убрать
        /* @var $user User */
        //1630936800 - 06.09.2021 г
        if ($user && $user->activ == 1 && $user->is_agree_contract == 0 && $user->created_at < 1630936800){
            $user->is_agree_contract = 1;
            $course = Courses::getMlm();
            if ($course){
                $course->activateCourse($user);
            }
            $user->save();
            return $this->redirect('/');

        }
        if ($user && $user->activ == 1){
            $course = Courses::getMlm();
            if ($course){
                $course->activateCourse($user);
            }
        }

        $professions = Courses::find()->where(['type'=>[Courses::MLM, Courses::INVEST, Courses::CRIPTO]])->all();
        $courses = Courses::find()->where(['type' => Courses::ONLINE_COURSE])->all();
        $reviews = CourseReviews::find()->all();
        return $this->render('index',[
            'user' => $user,
            'professions' => $professions,
            'courses' => $courses,
            'reviews' => $reviews,
        ]);
    }

    public function actionRekassa(){
        $model = new RekassaForm();
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                $model->file = $file;
                if($model->validate(['file'])){
                    $dir = Yii::getAlias('@frontend/web/certs/');
                    $model->file->saveAs($dir.'rekassa.pdf');
                    Yii::$app->getSession()->setFlash('success', 'Файл сохранен');
                    return $this->refresh();

                }
            }
        }
        return $this->render('rekassa',['model'=>$model]);
    }
}
