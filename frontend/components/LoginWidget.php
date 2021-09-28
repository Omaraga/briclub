<?php
namespace frontend\components;

use frontend\models\LoginForm;
use Yii;
use yii\base\Widget;

class LoginWidget extends Widget
{
    public function run() {

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) and $model->login()) {
            return true;
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
}