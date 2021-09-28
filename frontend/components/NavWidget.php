<?php
namespace frontend\components;

use common\models\Beds5;
use common\models\Courses;
use common\models\Lessons;
use common\models\UserPlatforms;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class NavWidget extends Widget
{

    public function run() {
        $activ = false;
        $user = UserPlatforms::find()->where(['user_id'=>Yii::$app->user->identity['id']])->one();
        if(!empty($user)){
            $activ = true;
        }
        return $this->render('nav', [
            'activ' => $activ
        ]);

    }
}