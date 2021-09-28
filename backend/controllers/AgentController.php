<?php

namespace backend\controllers;

use common\models\Actions;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\AgentForm;
use yii\filters\VerbFilter;
use common\models\User;

/**
 * AgentController implements the CRUD actions for User model.
 */
class AgentController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['is_agent' => 1]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => Actions::find()->where(['user_id' => $model->id]),
        ]);
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AgentForm();
        $users = ArrayHelper::getColumn(User::find()->where(['activ'=> null])->select('username')->asArray()->all(),'username');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Представитель создан');
            return $this->redirect('/agent');
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionBlock($id)
    {
        $model = $this->findModel($id);


        $model->block = 1;
        $model->agent_status = AgentForm::BLOCK;
        $model->save('false');


        Yii::$app->session->setFlash('success', 'Представитель заблокирован');
        return $this->redirect('/agent');
    }
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //$model->block = 1;
        $model->is_agent = null;
        $model->agent_status = AgentForm::BLOCK;
        $model->save('false');
        Yii::$app->session->setFlash('success', 'Представитель удален');
        return $this->redirect('/agent');

    }
    public function actionActive($id)
    {
        $model = $this->findModel($id);

        $model->block = 2;
        $model->is_agent = 1;
        $model->agent_status = AgentForm::ACTIVE;
        $model->save('false');


        Yii::$app->session->setFlash('success', 'Представитель активирован');
        return $this->redirect('/agent');
    }



    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
