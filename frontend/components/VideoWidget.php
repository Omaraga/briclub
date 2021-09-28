<?php
namespace frontend\components;

use common\models\Beds5;
use common\models\Courses;
use common\models\Lessons;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class VideoWidget extends Widget
{
    public $course_id;

    public function run() {
        $course = Courses::findOne($this->course_id);
        if(!empty($course['price'])){
            if(!\Yii::$app->user->isGuest and \common\models\UserCourses::isAccess(Yii::$app->user->identity['id'],$this->course_id)){
                $video_type = "vimeo_link";
                $link = Lessons::find()->where(['course_id'=>$this->course_id])->one()[$video_type];
                $link = explode("/",$link);
                $link = $link[count($link)-1];
                $frame = '<iframe title="vimeo-player" src="https://player.vimeo.com/video/'.$link.'" width="100%" height="360" frameborder="0" allowfullscreen></iframe>';
            }else{
                $frame = "У вас нет доступа";
            }

        }else{
            $video_type = "youtube_link";
            $link = Lessons::find()->where(['course_id'=>$this->course_id])->one()[$video_type];
            $link = explode("/",$link);
            $link = $link[count($link)-1];
            $frame = '<iframe width="100%" height="360" src="https://www.youtube.com/embed/'.$link.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        return $this->render('video', [
            'course_id' => $this->course_id,
            'frame' => $frame,
        ]);

    }
}