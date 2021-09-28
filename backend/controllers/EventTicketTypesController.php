<?php

namespace backend\controllers;

use Yii;
use common\models\EventTicketTypes;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * EventTicketTypesController implements the CRUD actions for EventTicketTypes model.
 */
class EventTicketTypesController extends Controller
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
     * Lists all EventTicketTypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EventTicketTypes::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EventTicketTypes model.
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
     * Creates a new EventTicketTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EventTicketTypes();

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
            return $this->redirect('/events/view?id='.$model->event_id);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EventTicketTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
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
            return $this->redirect('/events/view?id='.$model->event_id);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EventTicketTypes model.
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
     * Finds the EventTicketTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EventTicketTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EventTicketTypes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
