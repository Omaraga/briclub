<?php

namespace backend\controllers;

use Yii;
use common\models\Audios;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AudiosController implements the CRUD actions for Audios model.
 */
class AudiosController extends Controller
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
     * Lists all Audios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Audios::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Audios model.
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
     * Creates a new Audios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($lib_id = null)
    {
        $model = new Audios();

        if ($model->load(Yii::$app->request->post())) {
            if($model->validate(['file'])){
                $file = UploadedFile::getInstances($model, 'file');
                if($file && $file[0]->tempName){
                    $model->file = $file;
                    $dir = Yii::getAlias('@frontend/web/docs/audios/');
                    $dir2 = Yii::getAlias('docs/audios/');
                    foreach ($model->file as $file){
                        $rand = rand(900000,9000000);
                        $fileName = $rand . '.' . $file->extension;
                        $file->saveAs($dir . $rand / 1000 . $fileName);

                        $audio = new Audios();
                        $audio->title = $model->title;
                        $audio->status = $model->status;
                        $audio->lib_id = $model->lib_id;
                        $audio->file = $fileName;
                        $link = '/'.$dir2 . $rand / 1000 . $fileName;
                        $audio->link = $link;
                        $audio->time = time();
                        $audio->save(false);
                    }
//                    $model->file = $fileName; // без этого ошиб ка

                    return $this->redirect('/audios');
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'lib_id' => $lib_id,
        ]);
    }

    /**
     * Updates an existing Audios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('/library/view?id='.$model->lib_id);
        }

        return $this->render('update', [
            'model' => $model,
            'lib_id' => $model->lib_id
        ]);
    }

    /**
     * Deletes an existing Audios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect($_SERVER["HTTP_REFERER"]);
    }

    /**
     * Finds the Audios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Audios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Audios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
