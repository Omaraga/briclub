<?php
namespace frontend\components;

use common\models\Beds6;
use yii\base\Widget;
use yii\helpers\Html;

class SubscribeWidget extends Widget
{
    public $course_id;
    public $text;

    public function run() {
        $model = new Beds6();

        if($model->load(\Yii::$app->request->post())){
            $model->course_id = $this->course_id;
            $model->type = 5;
            $model->save();
        }
        return $this->render('subscribe', [
            'course_id' => $this->course_id,
            'text' => $this->text,
        ]);

    }
}