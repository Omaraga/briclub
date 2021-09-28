<?php

namespace backend\controllers;

use common\models\Content;
use common\models\ContentGroups;
use common\models\ContentTypes;
use common\models\User;
use Yii;
use common\models\Groups;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
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
     * Lists all Groups models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Groups::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Groups model.
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
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($screen_id,$c_id,$group_id)
    {
        if(!User::isAccess('moderator')){
            Yii::$app->user->logout();
            return $this->goHome();
        }
        $group = ContentGroups::findOne($group_id);
        $model = new Groups();
        $model->title = $group['title'];
        $model->group_id = $group_id;
        $model->course_screen_id = $c_id;
        $model->screen_id = $screen_id;
        $model->save();

        $types = ContentTypes::find()->where(['group_id'=>$group_id])->all();
        foreach ($types as $type) {
            $content = new Content();
            $content->type = $type['id'];
            $content->title = "Новый ".$type['title'];
            $content->screen_course_id = $c_id;
            $content->group_id = $model->id;
            $content->save();
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Updates an existing Groups model.
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
     * Deletes an existing Groups model.
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
        $contents = Content::find()->where(['group_id'=>$id])->all();
        foreach ($contents as $content) {
            $c1 = Content::findOne($content['id']);
            $c1->delete();
        }

        $this->findModel($id)->delete();

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
