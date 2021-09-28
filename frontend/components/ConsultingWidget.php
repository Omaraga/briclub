<?php
namespace frontend\components;

use common\models\Beds3;
use yii\base\Widget;
use yii\helpers\Html;

class ConsultingWidget extends Widget
{
    public $course_id;

    public function run() {
        $model = new Beds3();

        if($model->load(\Yii::$app->request->post())){
            $model->course_id = $this->course_id;
            $model->type = 2;
            $model->save();
        }
        return $this->render('consulting', [
            'course_id' => $this->course_id,
        ]);

    }
}