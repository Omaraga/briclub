<?php
namespace frontend\components;

use common\models\Beds;
use common\models\Courses;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class BedWidget extends Widget
{
    public $course_id;


    public function run() {
        $model = new Beds();
        $user = null;
        $mes = false;
        if(!Yii::$app->user->isGuest){
            $user = Yii::$app->user->identity;
            $bed = Beds::find()->where(['user_id'=>$user['id'],'course_id'=>$this->course_id])->one();
            if(isset($bed['course_id']) and !empty($bed['course_id'])){
                $mes = true;
            }
        }


        if($model->load(\Yii::$app->request->post())){
            $model->course_id = $this->course_id;
            $model->type = 1;
            $model->created_at = time();
            $model->user_id = $user['id'];
            $model->save();
            //return true;
        }

        return $this->render('bed', [
            'model' => $model,
            'user' => $user,
            'mes' => $mes,
        ]);

    }

}