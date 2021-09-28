<?php
namespace frontend\components;

use common\models\Beds4;
use yii\base\Widget;
use yii\helpers\Html;

class ConsultWidget extends Widget
{
    public $course_id;

    public function run() {
        $model = new Beds4();

        if($model->load(\Yii::$app->request->post())){
            $model->course_id = $this->course_id;
            $model->type = 2;
            $model->save();
        }
        return $this->render('consult', [
            'course_id' => $this->course_id,
        ]);

    }
}