<?php

namespace backend\controllers;

use common\models\Library;
use Yii;
use common\models\Documents;
use yii\data\ActiveDataProvider;
use yii\validators\FileValidator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DocumentsController implements the CRUD actions for Documents model.
 */
class LibraryController extends Controller
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
     * Lists all Documents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Library::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Documents model.
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
     * Creates a new Documents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Library();

        if ($model->load(Yii::$app->request->post())) {

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

            $file2 = UploadedFile::getInstance($model, 'file2');
            $link2 = null;
            if ($file2 && $file2->tempName) {
                $model->file2 = $file2;
                if (/*$model->validate(['file2'])*/true) {

                    $rand = rand(900000,9000000);
                    $dir = Yii::getAlias('@frontend/web/docs/');
                    $dir2 = Yii::getAlias('docs/');
                    $fileName = $rand . '.' . $model->file2->extension;
                    $model->file2->saveAs($dir . $fileName);
                    $model->file2 = $fileName; // без этого ошибка
                    $link2 = '/'.$dir2 . $fileName;
                }
            }
            $model->link2 = $link2;

            $model->save();
            return $this->redirect('/library');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Documents model.
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

            $file2 = UploadedFile::getInstance($model, 'file2');
            $link2 = null;
            if ($file2 && $file2->tempName) {
                $model->file2 = $file2;
                if ($model->validate(['file2'])) {

                    $rand = rand(900000,9000000);
                    $dir = Yii::getAlias('@frontend/web/docs/');
                    $dir2 = Yii::getAlias('docs/');
                    $fileName = $rand . '.' . $model->file2->extension;
                    $model->file2->saveAs($dir . $fileName);
                    $model->file2 = $fileName; // без этого ошибка
                    $link2 = '/'.$dir2 . $fileName;
                }
            }
            $model->link2 = $link2;

            $model->save();
            return $this->redirect('/library');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Documents model.
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
     * Finds the Documents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Documents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Library::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
