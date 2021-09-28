<?php

namespace backend\controllers;

use common\models\Content;
use Faker\Factory;
use common\models\ContentGroups;
use common\models\ContentTypes;
use common\models\CourseScreens;
use common\models\Groups;
use common\models\Lessons;
use common\models\Parts;
use common\models\User;
use common\models\UserCourses;
use Symfony\Component\BrowserKit\Tests\ClientTest;
use Yii;
use common\models\Courses;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Inflector;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use dastanaron\translit\Translit;
use yii\web\UploadedFile;

/**
 * CoursesController implements the CRUD actions for Courses model.
 */
class CoursesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Courses models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Courses::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Courses model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Courses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new Courses();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $str = $model->title;
            $translit = new Translit();
            if(empty($model->alias)){
                $model->alias = mb_strtolower($translit->translit($str, true, 'ru-en'));
            }
            if($model->save()){
                $screen2 = new CourseScreens();
                $screen2->screen_id = 2;
                $screen2->position = 2;
                $screen2->title = 'Главный экран';
                $screen2->course_id = $model->id;
                $screen2->save();

                $content_types = ContentTypes::find()->where(['screen_id'=>$screen2->screen_id])->all();
                $k = 0;
                $groups = array();
                foreach ($content_types as $content_type) {
                    $count = $content_type['count'];
                    if($content_type['count']>1  and $content_type['group_id']>0){
                        $k++;
                        for($i=1;$i<$count+1;$i++){
                            if($k<2){
                                $group = new Groups();
                                $group->title = ContentGroups::findOne($content_type['group_id'])['title'];
                                $group->screen_id = $content_type['screen_id'];
                                $group->course_screen_id = $screen2->id;;
                                $group->group_id = $content_type['group_id'];
                                $group->save();
                                $groups[$i] = $group->id;

                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen2->id;;
                                $content->group_id = $group->id;
                                $content->save();
                            }else{
                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen2->id;;
                                $content->group_id = $groups[$i];
                                $content->save();
                            }

                        }
                    }else{
                        for($i=1;$i<$count+1;$i++){
                            $content = new Content();
                            $content->type = $content_type['id'];
                            $content->title = $content_type['title'];
                            $content->screen_course_id = $screen2->id;;
                            $content->save();
                        }
                    }

                }

                $screen3 = new CourseScreens();
                $screen3->screen_id = 4;
                $screen3->position = 3;
                $screen3->course_id = $model->id;
                $screen3->title = 'Для кого курс';
                $screen3->save();

                $content_types = ContentTypes::find()->where(['screen_id'=>$screen3->screen_id])->all();
                $k = 0;
                $groups = array();
                foreach ($content_types as $content_type) {
                    $count = $content_type['count'];
                    if($content_type['count']>1 and $content_type['group_id']>0){
                        $k++;
                        for($i=1;$i<$count+1;$i++){
                            if($k<2){
                                $group = new Groups();
                                $group->title = ContentGroups::findOne($content_type['group_id'])['title'];
                                $group->screen_id = $content_type['screen_id'];
                                $group->course_screen_id = $screen3->id;;
                                $group->group_id = $content_type['group_id'];
                                $group->save();
                                $groups[$i] = $group->id;

                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen3->id;;
                                $content->group_id = $group->id;
                                $content->save();
                            }else{
                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen3->id;;
                                $content->group_id = $groups[$i];
                                $content->save();
                            }

                        }
                    }else{
                        for($i=1;$i<$count+1;$i++){

                            $content = new Content();
                            $content->type = $content_type['id'];
                            $content->title = $content_type['title'];
                            $content->screen_course_id = $screen3->id;;
                            $content->save();
                        }
                    }

                }

                $screen1 = new CourseScreens();
                $screen1->screen_id = 11;
                $screen1->position = 4;
                $screen1->title = 'Навыки';
                $screen1->course_id = $model->id;
                $screen1->save();

                $content_types = ContentTypes::find()->where(['screen_id'=>$screen1->screen_id])->all();
                $k = 0;
                $groups = array();
                foreach ($content_types as $content_type) {
                    $count = $content_type['count'];
                    if($content_type['count']>1  and $content_type['group_id']>0){
                        $k++;
                        for($i=1;$i<$count+1;$i++){
                            if($k<2){
                                $group = new Groups();
                                $group->title = ContentGroups::findOne($content_type['group_id'])['title'];
                                $group->screen_id = $content_type['screen_id'];
                                $group->course_screen_id = $screen1->id;;
                                $group->group_id = $content_type['group_id'];
                                $group->save();
                                $groups[$i] = $group->id;

                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen1->id;;
                                $content->group_id = $group->id;
                                $content->save();
                            }else{
                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen1->id;;
                                $content->group_id = $groups[$i];
                                $content->save();
                            }

                        }
                    }else{
                        for($i=1;$i<$count+1;$i++){

                            $content = new Content();
                            $content->type = $content_type['id'];
                            $content->title = $content_type['title'];
                            $content->screen_course_id = $screen1->id;;
                            $content->save();
                        }
                    }

                }
                $screen3 = new CourseScreens();
                $screen3->screen_id = 7;
                $screen3->position = 5;
                $screen3->course_id = $model->id;
                $screen3->title = 'Лекторы';
                $screen3->save();

                $content_types = ContentTypes::find()->where(['screen_id'=>$screen3->screen_id])->all();
                $k = 0;
                $groups = array();
                foreach ($content_types as $content_type) {
                    $count = $content_type['count'];
                    if($content_type['count']>1 and $content_type['group_id']>0){
                        $k++;
                        for($i=1;$i<$count+1;$i++){
                            if($k<2){
                                $group = new Groups();
                                $group->title = ContentGroups::findOne($content_type['group_id'])['title'];
                                $group->screen_id = $content_type['screen_id'];
                                $group->course_screen_id = $screen3->id;;
                                $group->group_id = $content_type['group_id'];
                                $group->save();
                                $groups[$i] = $group->id;

                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen3->id;;
                                $content->group_id = $group->id;
                                $content->save();
                            }else{
                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen3->id;;
                                $content->group_id = $groups[$i];
                                $content->save();
                            }

                        }
                    }else{
                        for($i=1;$i<$count+1;$i++){

                            $content = new Content();
                            $content->type = $content_type['id'];
                            $content->title = $content_type['title'];
                            $content->screen_course_id = $screen3->id;;
                            $content->save();
                        }
                    }

                }

                $screen3 = new CourseScreens();
                $screen3->screen_id = 6;
                $screen3->position = 6;
                $screen3->course_id = $model->id;
                $screen3->title = 'Программа курса';
                $screen3->save();

                $content_types = ContentTypes::find()->where(['screen_id'=>$screen3->screen_id])->all();
                $k = 0;
                $groups = array();
                foreach ($content_types as $content_type) {
                    $count = $content_type['count'];
                    if($content_type['count']>1 and $content_type['group_id']>0){
                        $k++;
                        for($i=1;$i<$count+1;$i++){
                            if($k<2){
                                $group = new Groups();
                                $group->title = ContentGroups::findOne($content_type['group_id'])['title'];
                                $group->screen_id = $content_type['screen_id'];
                                $group->course_screen_id = $screen3->id;;
                                $group->group_id = $content_type['group_id'];
                                $group->save();
                                $groups[$i] = $group->id;

                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen3->id;;
                                $content->group_id = $group->id;
                                $content->save();
                            }else{
                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $screen3->id;;
                                $content->group_id = $groups[$i];
                                $content->save();
                            }

                        }
                    }else{
                        for($i=1;$i<$count+1;$i++){

                            $content = new Content();
                            $content->type = $content_type['id'];
                            $content->title = $content_type['title'];
                            $content->screen_course_id = $screen3->id;;
                            $content->save();
                        }
                    }

                }
                $this->saveFile($model);
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionPositionUp($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $screen = CourseScreens::findOne($id);
        $pred_screen = CourseScreens::find()->where(['course_id'=>$screen['course_id']])->andWhere(['<','position',$screen['position']])->orderBy('position desc')->one();
        $this_pos = $pred_screen['position'];
        $pred_screen = CourseScreens::findOne($pred_screen['id']);
        $pred_screen->position = $screen['position'];
        $pred_screen->save();

        $screen->position = $this_pos;
        $screen->save();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionPositionDown($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $screen = CourseScreens::findOne($id);
        $next_screen = CourseScreens::find()->where(['course_id'=>$screen['course_id']])->andWhere(['>','position',$screen['position']])->orderBy('position asc')->one();
        $this_pos = $next_screen['position'];
        $next_screen = CourseScreens::findOne($next_screen['id']);
        $next_screen->position = $screen['position'];
        $next_screen->save();

        $screen->position = $this_pos;
        $screen->save();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionStatus($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $screen = CourseScreens::findOne($id);
        if($screen['is_active'] == 0){
            $status = 1;
        }else{
            $status = 0;
        }
        $screen->is_active = $status;
        $screen->save();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    /**
     * Updates an existing Courses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = $this->findModel($id);
        //$events =  \HttpRequest::get('https://api.eventum.one/v1/event/list/92');
       /*$client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl('https://api.eventum.one/v1/event/list/92')
            ->send();
        $events = $response->data['data'];
        $events_list = array();
        foreach ($events as $event) {
            $events_list[$event['eventId']] = $event['name'];
        }*/
        if ($model->load(Yii::$app->request->post())) {
			$str = $model->title;
            $translit = new Translit();
            if(empty($model->alias)){
                $model->alias = mb_strtolower($translit->translit($str, true, 'ru-en'));
            }
			$model->save();
            $this->saveFile($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            //'events' => $events_list,
        ]);
    }

    /**
     * Deletes an existing Courses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $course_screens = CourseScreens::find()->where(['course_id'=>$id])->all();
        foreach ($course_screens as $course_screen) {
            $contents = Content::find()->where(['screen_course_id'=>$course_screen['id']])->all();
            foreach ($contents as $content) {
                $c1 = Content::findOne($content['id']);
                $c1->delete();
            }
            $groups = Groups::find()->where(['course_screen_id'=>$course_screen['id']])->all();
            foreach ($groups as $group) {
                $g1 = Groups::findOne($group['id']);
                $g1->delete();
            }
            $cs1 = CourseScreens::findOne($course_screen['id']);
            $cs1->delete();
        }
        $parts = Parts::find()->where(['course_id'=>$id])->all();
        foreach ($parts as $part) {
            $part = Parts::findOne($part['id']);
            $part->delete();
        }
        $lessons = Lessons::find()->where(['course_id'=>$id])->all();
        foreach ($lessons as $lesson) {
            $lesson = Lessons::findOne($lesson['id']);
            $lesson->delete();
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function saveFile(Courses $model){
        $file = UploadedFile::getInstance($model, 'icon');
        $link = null;
        if ($file && $file->tempName) {
            $model->icon = $file;
            $rand = rand(900000,9000000);
            $dir = Yii::getAlias('@frontend/web/img/course/');
            $dir2 = Yii::getAlias('img/course/');
            $fileName = $rand . '.' . $model->icon->extension;
            $model->icon->saveAs($dir . $fileName);
            $model->icon = $fileName; // без этого ошибка
            $link = '/'.$dir2 . $fileName;
            $model->icon_url = $link;
            $model->save(false);
        }
        $file = UploadedFile::getInstance($model, 'preview');
        $link = null;
        if ($file && $file->tempName) {
            $model->icon = $file;
            $rand = rand(900000,9000000);
            $dir = Yii::getAlias('@frontend/web/img/course/');
            $dir2 = Yii::getAlias('img/course/');
            $fileName = $rand . '.' . $model->icon->extension;
            $model->icon->saveAs($dir . $fileName);
            $model->icon = $fileName; // без этого ошибка
            $link = '/'.$dir2 . $fileName;
            $model->preview_url = $link;
            $model->save(false);
        }

    }

    public function actionSetMlm(){
        $users = User::find()->where(['activ' => 1])->all();
        $mlmId = Courses::getMlm()->id;
        foreach ($users as $user){
            if (UserCourses::isAccess($user->id, $mlmId)){
                continue;
            }
            $model = new UserCourses();
            $model->user_id = $user->id;
            $model->course_id = $mlmId;
            $model->date = time();
            $model->save(false);
        }
    }

    public function actionCreateMlm(){
        $mlm = Courses::getMlm();
        if (!$mlm){
            $mlm = new Courses();
            $mlm->price = 103;
            $mlm->title = 'MLM предприниматель';
            $mlm->type = Courses::MLM;
            $mlm->duration = '12 месяцев';
            $mlm->soon = 2;
            $mlm->created_at = time();
            $mlm->is_active = 1;
            $mlm->save(false);
        }

        $parts = Parts::find()->where(['course_id' => $mlm->id, 'is_intro' => 0])->all();
        if (sizeof($parts) < 6){
            for ($i = 0; $i < 6 - sizeof($parts); $i++){
                $newPart = new Parts();
                $newPart->course_id = $mlm->id;
                $newPart->is_intro = 0;
                $newPart->free = 0;
                $newPart->save(false);
            }
        }
        $parts = Parts::find()->where(['course_id' => $mlm->id, 'is_intro' => 0])->all();
        $counter = 1;
        /* @var $parts Parts[]*/
        $faker = Factory::create();
        foreach($parts as $part){
            $part->position = $counter;
            $part->title = $counter.' модуль';
            $part->save(false);
            $counter++;
            $lessons = Lessons::find()->where(['part_id' => $part->id, 'course_id' => $mlm->id])->orderBy('position ASC')->all();
            if (sizeof($lessons) < 7){
                for ($i = 0; $i < 7 - sizeof($lessons); $i++){
                    $newLesson = new Lessons();
                    $newLesson->course_id = $mlm->id;
                    $newLesson->part_id = $part->id;
                    $newLesson->description = $faker->text(rand(100, 200));
                    $newLesson->save(false);
                }
            }
            $lessons = Lessons::find()->where(['part_id' => $part->id, 'course_id' => $mlm->id])->orderBy('position ASC')->all();
            $lessonCounter = 1;
            foreach ($lessons as $lesson){
                $lesson->position = $lessonCounter;
                $lesson->title = $lessonCounter. " урок";
                $lesson->save(false);
                $lessonCounter++;
            }
        }

    }

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
