<?php

namespace backend\controllers;

use common\models\Content;
use common\models\ContentGroups;
use common\models\ContentTypes;
use common\models\Groups;
use common\models\Screens;
use common\models\User;
use Yii;
use common\models\CourseScreens;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * CourseScreensController implements the CRUD actions for CourseScreens model.
 */
class CourseScreensController extends Controller
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
     * Lists all CourseScreens models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CourseScreens::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CourseScreens model.
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
     * Creates a new CourseScreens model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new CourseScreens();
        if($model->load(Yii::$app->request->post())){
            $model->title = Screens::findOne($model->screen_id)['title'];
            $model->position = CourseScreens::find()->where(['not in','screen_id',3])->andWhere(['course_id'=>$model->course_id])->orderBy('position desc')->one()['position']+1;
            if ($model->save()) {
                $content_types = ContentTypes::find()->where(['screen_id'=>$model->screen_id])->all();
                $k = 0;
                $groups = array();
                foreach ($content_types as $content_type) {
                    $count = $content_type['count'];
                    if($content_type['group_id']>0){
                        $k++;
                        for($i=1;$i<$count+1;$i++){
                            if($k<2){
                                $group = new Groups();
                                $group->title = ContentGroups::findOne($content_type['group_id'])['title'];
                                $group->screen_id = $content_type['screen_id'];
                                $group->course_screen_id = $model->id;
                                $group->group_id = $content_type['group_id'];
                                $group->save();
                                $groups[$i] = $group->id;

                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $model->id;
                                $content->group_id = $group->id;
                                $content->save();
                            }else{
                                $content = new Content();
                                $content->type = $content_type['id'];
                                $content->title = $content_type['title']." ".$i;
                                $content->screen_course_id = $model->id;
                                $content->group_id = $groups[$i];
                                $content->save();
                            }

                        }
                    }else{
                        for($i=1;$i<$count+1;$i++){

                            $content = new Content();
                            $content->type = $content_type['id'];
                            $content->title = $content_type['title'];
                            $content->screen_course_id = $model->id;
                            $content->save();
                        }
                    }

                }
                return $this->redirect(['/courses/view', 'id' => $model->course_id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CourseScreens model.
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CourseScreens model.
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
        $contents = Content::find()->where(['screen_course_id'=>$id])->all();
        foreach ($contents as $content) {
            $c1 = Content::findOne($content['id']);
            $c1->delete();
        }
        $groups = Groups::find()->where(['course_screen_id'=>$id])->all();
        foreach ($groups as $group) {
            $g1 = Groups::findOne($group['id']);
            $g1->delete();
        }
        $course_id = $this->findModel($id)['course_id'];

        $this->findModel($id)->delete();

        return $this->redirect(['courses/view?id='.$course_id]);
    }

    /**
     * Finds the CourseScreens model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourseScreens the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourseScreens::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
