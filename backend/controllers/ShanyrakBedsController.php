<?php

namespace backend\controllers;

use common\models\ShanyrakUser;
use common\models\ShanyrakUserPays;
use Yii;
use common\models\ShanyrakBeds;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\User;

/**
 * ShanyrakBedsController implements the CRUD actions for ShanyrakBeds model.
 */
class ShanyrakBedsController extends Controller
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
     * Lists all ShanyrakBeds models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ShanyrakBeds::find()->orderBy('id desc'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShanyrakBeds model.
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
     * Creates a new ShanyrakBeds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShanyrakBeds();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ShanyrakBeds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSuccess($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file2');
            $link = null;
            if ($file && $file->tempName) {
                $model->file = $file;
                if ($model->validate(['file2'])) {

                    $rand = rand(900000,9000000);
                    $dir = Yii::getAlias('@frontend/web/docs/shanyrak/');
                    $dir2 = Yii::getAlias('docs/shanyrak/');
                    $fileName = $rand . '.' . $model->file->extension;
                    $model->file->saveAs($dir . $fileName);
                    $model->file = $fileName; // без этого ошибка
                    $link = '/'.$dir2 . $fileName;
                }
            }
            $model->doc2 = $link;
            $model->status = 1;
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('success', [
            'model' => $model,
        ]);
    }
    public function actionSuccess2($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        $model->save();


        $user_id = \common\models\User::findOne($model['user_id'])['id'];
        $program_id = $model->program_id;
        $user_shanyrak = ShanyrakUser::find()->where(['user_id'=>$user_id,'program_id'=>$program_id])->one();
        $user_bed_id = $model->id;

        $shanyrak_user_pay = new ShanyrakUserPays();
        $shanyrak_user_pay->program_id = $program_id;
        $shanyrak_user_pay->user_id = $user_id;
        $shanyrak_user_pay->bed_id = $user_bed_id;
        $shanyrak_user_pay->user_shanyrak_id = $user_shanyrak['id'];
        $shanyrak_user_pay->type = 1;
        $shanyrak_user_pay->status = 2;
        $shanyrak_user_pay->sum_need = $model->sum_first;
        $shanyrak_user_pay->pan = null;
        $shanyrak_user_pay->save();

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing ShanyrakBeds model.
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
     * Finds the ShanyrakBeds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShanyrakBeds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShanyrakBeds::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
