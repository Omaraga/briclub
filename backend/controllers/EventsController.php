<?php

namespace backend\controllers;

use common\models\Events;
use common\models\EventsAndRoles;
use common\models\News;
use Yii;
use common\models\Actions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ActionsController implements the CRUD actions for Actions model.
 */
class EventsController extends Controller
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
     * Lists all Actions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Events::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Actions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Actions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Events();

        if ($model->load(Yii::$app->request->post())) {
            $model->time = time();
            $file = UploadedFile::getInstance($model, 'file');
            $link = null;
            if ($file && $file->tempName) {
                $model->file = $file;
                if ($model->validate(['file'])) {

                    $rand = rand(900000,9000000);
                    $dir = Yii::getAlias('@frontend/web/docs/');
                    $dir2 = Yii::getAlias('docs/');
                    $fileName = $rand . '.' . $model->file->extension;
                    $model->file->saveAs($dir . $fileName);
                    $model->file = $fileName; // без этого ошибка
                    $link = '/'.$dir2 . $fileName;
                }
            }

            $model->link = $link;
            $model->save();
            if ($model->roleList){
                foreach ($model->roleList as $role){
                    $eventRole = new EventsAndRoles();
                    $eventRole->event_id = $model->id;
                    $eventRole->role_id = $role;
                    $eventRole->save();

                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Actions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        /* @var $model Events*/
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {


            $file = UploadedFile::getInstance($model, 'file');
            $link = $model->link;
            if ($file && $file->tempName) {
                $model->file = $file;
                if ($model->validate(['file'])) {

                    $rand = rand(900000,9000000);
                    $dir = Yii::getAlias('@frontend/web/docs/');
                    $dir2 = Yii::getAlias('docs/');
                    $fileName = $rand . '.' . $model->file->extension;
                    $model->file->saveAs($dir . $fileName);
                    $model->file = $fileName; // без этого ошибка
                    $link = '/'.$dir2 . $fileName;
                }
            }
            $model->link = $link;
            $model->save();
            if ($model->roleList){
                foreach ($model->roleList as $role){
                    if (!EventsAndRoles::isHasPare($model, $role)){
                        $eventRole = new EventsAndRoles();
                        $eventRole->event_id = $model->id;
                        $eventRole->role_id = $role;
                        $eventRole->save();
                    }

                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Actions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the Actions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Actions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
