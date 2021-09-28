<?php

namespace backend\controllers;

use common\models\CourseScreens;
use common\models\Lessons;
use common\models\User;
use Yii;
use common\models\Parts;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartsController implements the CRUD actions for Parts model.
 */
class PartsController extends Controller
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
        ];
    }

    /**
     * Lists all Parts models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Parts::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Parts model.
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
     * Creates a new Parts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $model = new Parts();

        if ($model->load(Yii::$app->request->post())) {
            $part = Parts::find()->where(['course_id'=>$model->course_id])->orderBy('position desc')->one();
            if(!empty($part)){
                $model->position = $part['position'] + 1;
            }else{
                $model->position = 1;
            }
            $model->save();

            return $this->redirect(['/courses/view', 'id' => $model->course_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Parts model.
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

        if ($model->load(Yii::$app->request->post())) {

            $model->save();

            $lessons = Lessons::find()->where(['part_id'=>$model->id])->all();
            if(!empty($lessons)){
                foreach ($lessons as $lesson) {
                    $lesson = Lessons::findOne($lesson['id']);
                    $lesson->free = $model->free;
                    $lesson->save();
                }
            }
            return $this->redirect(['/courses/view', 'id' => $model->course_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Parts model.
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
        $model = $this->findModel($id);
        $course_id = $model->course_id;
        $model->delete();

        return $this->redirect(['/courses/view', 'id' => $course_id]);
    }


    public function actionPositionUp($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $part = Parts::findOne($id);
        $pred_part = Parts::find()->where(['course_id'=>$part['course_id']])->andWhere(['<','position',$part['position']])->orderBy('position desc')->one();
        $this_pos = $pred_part['position'];
        $pred_part = Parts::findOne($pred_part['id']);
        $pred_part->position = $part['position'];
        $pred_part->save();

        $part->position = $this_pos;
        $part->save();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionPositionDown($id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $part = Parts::findOne($id);
        $next_part = Parts::find()->where(['course_id'=>$part['course_id']])->andWhere(['>','position',$part['position']])->orderBy('position asc')->one();
        $this_pos = $next_part['position'];
        $next_part = Parts::findOne($next_part['id']);
        $next_part->position = $part['position'];
        $next_part->save();

        $part->position = $this_pos;
        $part->save();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Finds the Parts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Parts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
