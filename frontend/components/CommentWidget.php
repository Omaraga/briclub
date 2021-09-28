<?php
namespace frontend\components;

use common\models\Beds2;
use yii\base\Widget;
use yii\helpers\Html;

class CommentWidget extends Widget
{
    public $course_id;


    public function run() {
        $model = new Beds2();

        if($model->load(\Yii::$app->request->post())){
            $model->course_id = $this->course_id;
            $model->type = 4;
            $model->save();
        }
        return $this->render('comment', [
            'course_id' => $this->course_id,
        ]);

    }
}