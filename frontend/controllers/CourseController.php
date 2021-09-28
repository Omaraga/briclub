<?php
namespace frontend\controllers;

use common\models\Courses;
use common\models\User;
use common\models\UserCourses;
use common\models\UserHomeworks;
use common\models\UserLessons;
use frontend\models\forms\HomeworkForm;
use common\models\Lessons;
use common\models\Parts;
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
use yii\web\UploadedFile;

/**
 * Site controller
 */
class CourseController extends Controller
{
    public function actions()
    {
        return [
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => '/img/homeworks/', // Directory URL address, where files are stored.
                'path' => '@frontend/web/img/homeworks/' // Or absolute path to directory where files are stored.
            ],
        ];
    }
    public function actionIndex($id=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $course = Courses::findOne($id);
        if(!empty($course)){
            if(Yii::$app->user->isGuest){
                return $this->render('/site/error', ['name'=>'Пользователь не найден','message'=>'Авторизуйтесь чтобы смотреть страницу']);
            }else{
                /*if(!\common\models\UserCourses::isAccess(Yii::$app->user->identity['id'],$id)){
                    return $this->render('/site/error', ['name'=>'Отказано в доступе','message'=>'У вас нет доступа к курсу']);
                }else{*/
                    $lessons = Lessons::find()->where(['course_id'=>$id])->all();
                    $parts = Parts::find()->where(['course_id'=>$id])->orderBy('position asc')->all();
                    return $this->render('index',[
                        'course'=>$course,
                        'lessons'=>$lessons,
                        'parts'=>$parts,
                    ]);
                /*}*/
            }

        }else{
            return $this->render('/site/error', ['name'=>'404','message'=>'Course not found']);
        }

    }
    public function actionView($id=null)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        /*$users = User::find()->where(['activ'=>1])->all();

        foreach ($users as $user) {
            $course = UserCourses::find()->where(['user_id'=>$user['id'],'course_id'=>29])->one();
            if(empty($course)){
                $course = new UserCourses();
                $course->user_id = $user['id'];
                $course->course_id = 29;
                $course->date = time();
                $course->save();
            }
        }
        exit;*/

        $lesson = Lessons::findOne($id);
        $n_lesson = UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'status'=>2])->one();
        if(!empty($n_lesson)){
            if((time() - $n_lesson['start'])>=0){
                $n_lesson->status = 3;
                $n_lesson->save();
            }
        }
        if(!empty($lesson)){
            if(Yii::$app->user->isGuest){
                return $this->render('/site/error', ['name'=>'Пользователь не найден','message'=>'Авторизуйтесь чтобы смотреть страницу']);
            }else{
                $course = Courses::findOne($lesson['course_id']);
                if($course['type'] != 5){
                    $user_lessons = UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'lesson_id'=>$lesson['id']])->one();
                    if(empty($user_lessons)){
                        $user_lessons = new UserLessons();
                        $user_lessons->user_id = Yii::$app->user->id;
                        $user_lessons->lesson_id = $lesson['id'];
                        $user_lessons->course_id = $course['id'];
                        $user_lessons->start = time();
                        $user_lessons->status = 3;
                        $user_lessons->save();
                    }
                }
                $current_lesson = UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'lesson_id'=>$lesson['id']])->one();
                if(!\common\models\UserCourses::isAccess(Yii::$app->user->identity['id'],$course['id']) and $lesson['free'] == 0){
                    return $this->render('/site/error', ['name'=>'Отказано в доступе','message'=>'У вас нет доступа к уроку']);
                }else{
                    if(!empty($lesson['vimeo_link'])){

                            $video_type = "vimeo_link";
                            $link = $lesson[$video_type];
                            $link = explode("/",$link);
                            $link = $link[count($link)-1];
                            $frame = '<iframe title="vimeo-player" src="https://player.vimeo.com/video/'.$link.'" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>';


                    }else{
                        $video_type = "youtube_link";
                        $link = $lesson[$video_type];
                        $link = explode("/",$link);
                        $link = $link[count($link)-1];
                        $frame = '<iframe src="https://www.youtube.com/embed/'.$link.'" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                    }
                    $part = Parts::findOne($lesson['part_id']);
                    $next = Lessons::find()->where(['part_id'=>$part['id']])->andWhere(['>', 'position', $lesson['position']])->orderBy('position asc')->one();
                    $prev = Lessons::find()->where(['part_id'=>$part['id']])->andWhere(['<', 'position', $lesson['position']])->orderBy('position desc')->one();

                    $next_part = Parts::find()->where(['course_id'=>$course['id']])->andWhere(['>', 'position', $part['position']])->orderBy('position asc')->one();
                    $prev_part = Parts::find()->where(['course_id'=>$course['id']])->andWhere(['<', 'position', $part['position']])->orderBy('position asc')->one();
                    $next_id = null;
                    $prev_id = null;
                    $next_part_id = null;
                    $prev_part_id = null;
                    if(!empty($next)){
                        $next_id = $next['id'];
                    }
                    if(!empty($prev)){
                        $prev_id = $prev['id'];
                    }
                    if(!empty($next_part)){
                        $next_part_id = Lessons::find()->where(['part_id'=>$next_part['id']])->orderBy('position asc')->one()['id'];
                    }
                    if(!empty($prev_part)){
                        $prev_part_id = Lessons::find()->where(['part_id'=>$prev_part['id']])->orderBy('position desc')->one()['id'];
                    }


                    $model = new HomeworkForm();

                    if($model->load(Yii::$app->request->post()) and $model->validate()){


                        $user_h = new UserHomeworks();
                        $user_h->user_id = User::find()->where(['username'=>$model->user_id])->one()['id'];
                        if(!empty($user_h->user_id)){
                            $user_h->lesson_id = $lesson['id'];
                            $user_h->text = $model->text;

                            $file = UploadedFile::getInstance($model, 'file');
                            if ($file && $file->tempName) {
                                $model->file = $file;
                                if ($model->validate(['file'])) {

                                    $rand = rand(900000,9000000);
                                    $dir = Yii::getAlias('@frontend/web/img/homeworks/');
                                    $dir2 = Yii::getAlias('img/homeworks/');
                                    $fileName = $rand . '.' . $model->file->extension;
                                    $model->file->saveAs($dir . $fileName);
                                    $model->file = $fileName; // без этого ошибка
                                    $link = '/'.$dir2 . $fileName;
                                }
                                $user_h->link = $link;
                            }



                            $user_h->time = time();
                            if($user_h->save()){
                                if($course['type'] == 5){
                                    $user_lessons = UserLessons::find()->where(['user_id'=>Yii::$app->user->id,'lesson_id'=>$next_id])->one();
                                    if(empty($user_lessons)){
                                        $user_lessons = new UserLessons();
                                        $user_lessons->user_id = Yii::$app->user->id;
                                        $user_lessons->lesson_id = $next_id;
                                        $user_lessons->start = time() + 8*60*60;
                                        $user_lessons->course_id = $course['id'];
                                        $user_lessons->status = 2;
                                        $user_lessons->save();
                                    }
                                }

                                Yii::$app->getSession()->setFlash('success', Yii::t('users', 'Домашнее задание было загружено!'));
                            }else{
                                Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, попробуйте еще раз!'));
                            }
                        }else{
                            Yii::$app->getSession()->setFlash('danger', Yii::t('users', 'Произошла ошибка, пользователь не найден!'));
                        }

                    }

                    return $this->render('view',[
                        'course'=>$course,
                        'lesson'=>$lesson,
                        'frame'=>$frame,
                        'part'=>$part,
                        'next_id'=>$next_id,
                        'prev_id'=>$prev_id,
                        'next_part_id'=>$next_part_id,
                        'prev_part_id'=>$prev_part_id,
                        'model'=>$model,
                    ]);
                }
            }

        }else{
            return $this->render('/site/error', ['name'=>'404','message'=>'Lesson not found']);
        }

    }
}
