<?php

namespace frontend\controllers;

use common\models\BriTokens;
use common\models\DohodCompany;
use common\models\MatrixRef;
use common\models\Tokens;
use common\models\User;
use frontend\models\forms\BriTestForm;
use Yii;
class BritestController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->layout = '@frontend/views/layouts/main_old.php';
        return parent::beforeAction($action);
    }
    public function actionIndex()
    {

        $model = new BriTestForm();

        if ($model->load(Yii::$app->request->post())){
            BriTestForm::destroyData();
            $model->generateUsers();
            return $this->redirect('/britest/view');
        }


        return $this->render('index',[
            'model' => $model
        ]);
    }

    public function actionView(){

        $list = [];
        $list['balance_company'] = DohodCompany::find()->sum('sum');
        $list['total_pv'] = User::find()->sum('p_balans');
        $list['total_users'] = User::find()->count();
        $list['total_grc'] = Tokens::find()->sum('balans');
        $list['total_grc_users'] = Tokens::find()->count();
        $list['total_bri'] = BriTokens::find()->sum('balans');
        $list['total_bri_users'] = BriTokens::find()->count();
        $list['complete_third'] = MatrixRef::find()->where(['slots' => 4, 'platform_id' => 3])->count();
        $list['third'] = MatrixRef::find()->where(['platform_id' => 3])->count();
        $list['complete_second'] = MatrixRef::find()->where(['slots' => 4, 'platform_id' => 2])->count();
        $list['second'] = MatrixRef::find()->where(['platform_id' => 2])->count();
        $list['complete_first'] = MatrixRef::find()->where(['slots' => 4, 'platform_id' => 1])->count();
        $list['first'] = MatrixRef::find()->where(['platform_id' => 1])->count();
        $list['user_branch'] = User::find()->where(['parent_id' => null])->all();

        return $this->render('view', [
            'list' => $list,
        ]);

    }

    public function actionDestroy(){
        BriTestForm::destroyData();
        return $this->redirect('/britest');
    }

}
