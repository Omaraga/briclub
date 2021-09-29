<?php


namespace frontend\modules\academy\controllers;

use aki\telegram\base\Type;
use common\models\Acts;
use common\models\Courses;
use common\models\LessonDocs;
use common\models\Lessons;
use common\models\Parts;
use common\models\User;
use common\models\UserCourses;
use common\models\UserLessons;
use frontend\modules\academy\components\BaseAcademyController;
use frontend\modules\academy\models\forms\ReferralForm;
use http\Url;
use common\models\MatrixRef;
use kartik\mpdf\Pdf;
use yii\httpclient\Client;
use Yii;
use yii\helpers\ArrayHelper;

class CourseController extends BaseAcademyController
{
    /**
     * @return string| yii\web\Response
     */
    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            return $this->redirect('/site/login');
        }
        $user = Yii::$app->user->identity;
        $user_courses = UserCourses::find()->where(['user_id' => $user->id])->all();
        $userCourseIds = ArrayHelper::getColumn($user_courses, 'course_id');
        $courses = Courses::find()->where(['id' => $userCourseIds])->orderBy('type DESC')->all();

        return $this->render('index',[
            'user' => $user,
            'courses' => $courses,
        ]);
    }

    /**
     * @param null $id
     * @return string| yii\web\Response
     */
    public function actionAbout($id = null, $lessonId = null){
        if (!$id){
            return $this->redirect('/');
        }
        $course = Courses::findOne($id);
        if (!$course){
            return $this->redirect('/');
        }
        $user = Yii::$app->user->identity;
        //Вводный модуль
        $moduleIntro = $course->getIntroPart();
        //Основные модули
        $modules = Parts::find()->where(['course_id'=>$course->id])->andWhere(['is_intro'=>0])->all();
        $lesson = null;
        if ($lessonId){
            $lesson = Lessons::findOne($lessonId);
        }else if($moduleIntro){
            $lesson = Lessons::find()->where(['course_id'=>$course->id])->andWhere(['part_id' => $moduleIntro->id])->orderBy('position ASC')->one();
        }

        return $this->render('about',[
            'user' => $user,
            'course' => $course,
            'modules' => $modules,
            'moduleIntro' => $moduleIntro,
            'lesson' => $lesson,
        ]);
    }

    /**
     * @param null $id
     * @param null $lessonId
     * @return string| yii\web\Response
     */
    public function actionView($id = null, $lessonId = null)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect('/site/login');
        }
        $user = Yii::$app->user->identity;
        if ($id){
            $course = Courses::findOne($id);
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка курс не найден!'));
            return $this->redirect('/academy/course');
        }
        //проверяем доступ к курсу
        if (!UserCourses::isAccess($user->id, $course->id)){
            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка у вас нет доступа к данному курсу!'));
            return $this->redirect('/academy/course/about?id='.$course->id);
        }

        if ($course['type'] == Courses::MLM){
            return $this->redirect(['/academy/course/mlm', 'id' => $course->id, 'lessonId' => $lessonId]);
        }

        $modules = Parts::find()->where(['course_id'=>$course->id])->andWhere(['is_intro'=> 0])->orderBy('position ASC')->all();
        if (isset($lessonId)) { //если перешел на определенный урок
            $currLesson = Lessons::findOne($lessonId);
            if ($currLesson){
                if (!UserLessons::isLessonPassed($user->id, $lessonId)) { // временное решение, позже надо добавить доступ к урокам после прохождение пред урока
                    $userLesson = new UserLessons($user->id, $lessonId, $course->id);
                    $userLesson->save();
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка урок не найден!'));
                return $this->redirect('/academy/course');
            }

        }else{
            $userLesson = UserLessons::find()->where(['user_id' => $user->id, 'course_id' => $course->id])->orderBy('start DESC')->one();
            if ($userLesson){
                $currLesson = Lessons::findOne($userLesson->lesson_id);
            }else{
                $currLesson = Lessons::find()->where(['course_id' => $course->id])->orderBy('position ASC')->one();
            }


        }
        return $this->render('view',[
                'user' => $user,
                'course' => $course,
                'currLesson' => $currLesson,
                'modules'=> $modules,
        ]);
    }

    /**
     * @param null $id
     * @param null $lessonId
     * @return string| yii\web\Response
     */
    public function actionMlm($id = null, $lessonId = null){
        if(Yii::$app->user->isGuest){
            return $this->redirect('/site/login');
        }
        $user = Yii::$app->user->identity;
        if ($id){
            $course = Courses::findOne($id);
        }else{
            return $this->redirect('/academy/course');
        }
        if ($course['type'] == Courses::ONLINE_COURSE){
            return $this->redirect(['/academy/course/view', 'id' => $course->id, 'lessonId' => $lessonId]);
        }
        //проверяем доступ к курсу
        if (!UserCourses::isAccess($user->id, $course->id)){
            if ($user->activ == 1){
                $course->activateCourse($user);
            }else{
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка у вас нет доступа к данному курсу!'));
                return $this->redirect('/academy/course/about?id='.$course->id);
            }
        }
        $modules = Parts::find()->where(['course_id'=>$course->id])->andWhere(['is_intro'=> 0])->orderBy('position ASC')->all();

        if ($lessonId) { //если перешел на определенный урок
            $currLesson = Lessons::findOne($lessonId);

            if (!$currLesson){
                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка урок не найден!'));
                return $this->redirect('/academy/course');
            }else{
                if (!UserLessons::isLessonPassed($user->id, $lessonId)) { // временное решение, позже надо добавить доступ к урокам после прохождение пред урока
                    $userLesson = new UserLessons($user->id, $lessonId, $course->id);
                    $userLesson->save();
                }
            }

        }else{
            $userLesson = UserLessons::find()->where(['user_id' => $user->id, 'course_id' => $course->id])->orderBy('start DESC')->one();

            if ($userLesson){
                $currLesson = Lessons::findOne($userLesson->lesson_id);
            }else{
                $currLesson = Lessons::find()->where(['course_id' => $course->id, 'part_id' => $modules[0]->id])->orderBy('position ASC')->one();
            }

        }


        return $this->render('mlm',[
            'user' => $user,
            'course' => $course,
            'currLesson' => $currLesson,
            'modules'=> $modules,
        ]);
    }

    /**
     * @param null $id
     * @param int $level
     * @return string|yii\web\Response|null
     * @throws yii \base\InvalidConfigException
     */
    public function actionBuy($id = null, $level = 1){
        if(Yii::$app->user->isGuest){
            return $this->redirect('/site/login');
        }
        /* @var $user User*/
        $user = Yii::$app->user->identity;
		if($user['is_agent'] == 1){
            return $this->redirect('\academy/default');
        }
        if ($id){
            $course = Courses::findOne($id);
            if (!$course){
                Yii::$app->session->setFlash('danger', 'Курс не найден!');
                return $this->goHome();
            }
        }else{
            Yii::$app->session->setFlash('danger', 'Курс не найден!');
            return $this->goHome();
        }

        $referralForm = new ReferralForm();
        if ($user->parent_id){
            $referral = User::findOne($user->parent_id);
            $referralForm->referral = $referral;
            $referralForm->referralName = $referral->username;
        }
        $coursePrice = $course->price;
        if ($course->type == Courses::MLM){
            $referralForm->scenario = ReferralForm::SCENARIO_MLM;
            if ($level == 1){
                $coursePrice =  5000;
            }else if($level == 2){
                $coursePrice = 15000;
            }else if($level == 3){
                $coursePrice = 45000;
            }else if($level == 4){
                $coursePrice = 3600;
            }else if($level == 5){
                $coursePrice = 10800;
            }else if($level == 6){
                $coursePrice = 32400;
            }
        }else{
            $referralForm->scenario = ReferralForm::SCENARIO_ONLINE;
        }
        //print_r($referralForm->scenario);
        if (Yii::$app->request->isPost){
            $referralForm->load(Yii::$app->request->post());
            if ($referralForm->isReferral == 1){
                $referralForm->scenario = ReferralForm::SCENARIO_MLM_WITHOUT_REF;
            }

            if ($referralForm->scenario == ReferralForm::SCENARIO_MLM || $referralForm->scenario == ReferralForm::SCENARIO_MLM_WITHOUT_REF) {
                if ($referralForm->validate()) {
                    if (!$user->parent_id && $referralForm->referralName && $referralForm->scenario == ReferralForm::SCENARIO_MLM) {
                        $sponsor = User::find()->where(['username' => $referralForm->referralName])->one();
                        $user->parent_id = $sponsor->id;
                        $user->save();
                    }
                    if ($user->activ != 1) { // Если первый раз покупает MLM
                        if ($user['newmatrix'] == 1) {  // уже активирован
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Ошибка!'));
                            return $this->request->referrer;
                        }
                        if ($user->w_balans >= $coursePrice) {
                            MatrixRef::plusToRefMatrix($user['id'], 1, true, 0, true, null, 1);
                            $userCourse = $course->activateCourse($user);
                            $user->w_balans = $user->w_balans - $coursePrice;
                            $user->save();
                            try {
                                $this->createAct($user);
                            }catch (\yii\base\InvalidConfigException $e) {

                            }
                            Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вы успешно купили курс MLM предприниматель!'));
                            return $this->redirect('/');
                        }else{
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Недостаточно средств на балансе!'));
                            return $this->request->referrer;
                        }

                    } else { // Покупает другие уроки(то-есть другие места в матрице)
                        return $this->request->referrer;
                    }
                }
            }else if ($referralForm->scenario == ReferralForm::SCENARIO_ONLINE){ // покупка обычных курсов
                if ($user->w_balans >= $coursePrice) {
                    $user->w_balans = $user->w_balans - $coursePrice;
                    $user->save();

                    $userCourse = $course->activateCourse($user);
                    Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вы успешно купили курс '.$course->title.'!'));
                    return $this->redirect('/');
                } else {
                    Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Недостаточно средств на балансе!'));
                    return $this->request->referrer;
                }
            }

        }

        return $this->render('buy',[
                'user' => $user,
                'coursePrice' => $coursePrice,
                'course' => $course,
                'referralForm' => $referralForm,
            ]);
    }
	public function actionBuyfree($id = null){
        if(Yii::$app->user->isGuest){
            return $this->redirect('/site/login');
        }
        /* @var $user User*/
        $user = Yii::$app->user->identity;
        if($user['is_agent'] == 1){
            return $this->redirect('\academy/default');
        }
        if($id){
            $course = Courses::findOne($id);
            if (!$course){
                Yii::$app->session->setFlash('danger', 'Курс не найден!');
                return $this->goHome();
            }
            $userCourse = $course->activateCourse($user);
            Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Вы успешно получили курс '.$course->title.'!'));
            return $this->redirect('\academy/default');
        }else{
            Yii::$app->session->setFlash('danger', 'Курс не найден!');
            return $this->goHome();
        }
        return $this->redirect('/academy/course');
    }
    public function createAct($user){
        $content = $this->renderPartial('cert', [
            'sum' => 5000,
            'name' => $user['username']
        ]);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'cssFile' => '@frontend/web/css/cert.css',
            'mode' => Pdf::MODE_UTF8,
            'marginLeft' => 0,
            'marginRight' => 0,
            'marginTop' => 0,
            'marginBottom' => 0,
        ]);
        //return $pdf->render();
        $rand = rand(900000, 9000000);
        $dir = Yii::getAlias('@frontend/web/certs/');
        $fileName = $rand . '.pdf';
        $mpdf = $pdf->api;
        $stylesheet = ReferralForm::getStylesheetPdf();
        $mpdf->WriteHtml($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHtml($content);
        $mpdf->Output($dir . $fileName); // call the mpdf api output as needed

        $link = '/certs/' . $fileName;
        $act = new Acts();
        $act->user_id = $user['id'];
        $act->time = time();
        $act->link = $link;
        $act->save();
        Acts::sendEmail($user['id'], $link);
    }
}