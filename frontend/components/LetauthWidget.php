<?php
namespace frontend\components;

use common\models\Beds5;
use common\models\Courses;
use yii\base\Widget;
use yii\helpers\Html;

class LetauthWidget extends Widget
{
    public $course_id;


    public function run() {
        $course = Courses::findOne($this->course_id);
        if(!empty($course['price'])){
            $text = "Оплатить картой";
        }else{
            $text = "Оплатить картой";
        }
        return $this->render('letauth', [
            'course_id' => $this->course_id,
            'text' => $text,
        ]);

    }
}