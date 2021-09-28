<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lessons".
 *
 * @property int $id
 * @property string $title
 * @property string $vimeo_link
 * @property string $youtube_link
 * @property int $private
 * @property int $course_id
 * @property int $part_id
 * @property int $position
 * @property int $free
 * @property string $description
 * @property string $homework
 * @property int $image
 */
class Lessons extends \yii\db\ActiveRecord
{
    const LESSON_AVAILABLE = 1;
    const LESSON_NEED_BUY = 2;
    const LESSON_BLOCKED = 3;
    public $image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lessons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['private', 'course_id', 'part_id', 'position', 'free'], 'integer'],
            [['description', 'homework'], 'string'],
            [['title', 'vimeo_link', 'youtube_link'], 'string', 'max' => 255],
            [['icon'], 'string', 'max' => 510],
            [['image'], 'file'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'vimeo_link' => 'Ссылка Vimeo',
            'youtube_link' => 'Ссылка Youtube',
            'private' => 'Платный',
            'course_id' => 'Курс',
            'part_id' => 'Раздел',
            'free' => 'Бесплатно',
            'description' => 'Описание',
            'homework' => 'Домашнее задание',
        ];
    }

    /**
     * @return string
     */
    public function getFrame(){
        $frame = null;
        if(!empty($this->vimeo_link)){

            $link = $this->vimeo_link;
            $link = explode("/",$link);
            $link = $link[count($link)-1];
            $frame = '<iframe title="vimeo-player" src="https://player.vimeo.com/video/'.$link.'" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>';

        }else if(!empty($this->youtube_link)){
            $link = $this->youtube_link;
            $link = explode("/",$link);
            $link = $link[count($link)-1];
            $frame = '<iframe src="https://www.youtube.com/embed/'.$link.'" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        return $frame;
    }

    /**
     * @return array|LessonDocs[]| yii\db\ActiveRecord[]
     */
    public function getLessonDocs(){
        return LessonDocs::find()->where(['lesson_id' => $this->id, 'type' => 1])->all();
    }

    /**
     * @param $part_id
     * @return array|Lessons[]| yii\db\ActiveRecord[]
     */
    public static function getLessonsByPartId($part_id){
        return Lessons::find()->where(['part_id' => $part_id])->orderBy('position ASC')->all();
    }

    /**
     * @return int|void
     */
    public function getLessonPosition(){
        $lessons = Lessons::find()->where(['part_id' => $this->part_id, 'course_id' => $this->course_id])->orderBy('position ASC')->all();
        $counter = 1;
        foreach ($lessons as $lesson){
            if ($lesson->id == $this->id){
                return $counter;
            }
            $counter++;
        }
    }
    public function getPrice(){
        $course = Courses::findOne($this->course_id);
        if ($course->type == Courses::MLM){
            $module = Parts::findOne($this->part_id);
            $level = $module->getModulePosition();
            $coursePrice = 0;
            if ($level == 1){
                $coursePrice =  103;
            }else if($level == 2){
                $coursePrice = 300;
            }else if($level == 3){
                $coursePrice = 1200;
            }else if($level == 4){
                $coursePrice = 3600;
            }else if($level == 5){
                $coursePrice = 10800;
            }else if($level == 6){
                $coursePrice = 32400;
            }
            return $coursePrice;
        }else{
            return $course->price;
        }
    }
    /**
     * @param User $user
     * @return int
     */
    public function isLessonAvailable(User $user){
        $course = Courses::findOne($this->course_id);
        $module = Parts::findOne($this->part_id);
        $modulePos = $module->getModulePosition();
        $lessonPos = $this->getLessonPosition();
        if ($course->type == Courses::MLM){
            $mainMatrixRef = MatrixRef::find()->where(['user_id' => $user->id, 'platform_id' => $modulePos])->orderBy('id asc')->one();
            if (!empty($mainMatrixRef)){
                if ($lessonPos == 1){
                    if ($mainMatrixRef){
                        return self::LESSON_AVAILABLE;
                    }else{
                        return self::LESSON_NEED_BUY;
                    }
                }else if ($lessonPos == 2){
                    if ($mainMatrixRef->shoulder1){
                        return self::LESSON_AVAILABLE;
                    }else{
                        return self::LESSON_NEED_BUY;
                    }
                }else if($lessonPos == 3){
                    if ($mainMatrixRef->shoulder2){
                        return self::LESSON_AVAILABLE;
                    }else{
                        return self::LESSON_NEED_BUY;
                    }
                }else if($lessonPos == 4){
                    if ($mainMatrixRef->shoulder1_1){
                        return self::LESSON_AVAILABLE;
                    }else{
                        return self::LESSON_NEED_BUY;
                    }
                }else if($lessonPos == 5){
                    if ($mainMatrixRef->shoulder1_2){
                        return self::LESSON_AVAILABLE;
                    }else{
                        return self::LESSON_NEED_BUY;
                    }
                }else if($lessonPos == 6){
                    if ($mainMatrixRef->shoulder2_1){
                        return self::LESSON_AVAILABLE;
                    }else{
                        return self::LESSON_NEED_BUY;
                    }
                }else if($lessonPos == 7){
                    if ($mainMatrixRef->shoulder2_2){
                        return self::LESSON_AVAILABLE;
                    }else{
                        return self::LESSON_NEED_BUY;
                    }
                }else{
                    return self::LESSON_BLOCKED;
                }
            }else{
                return self::LESSON_NEED_BUY;
            }


        }else{
            return self::LESSON_AVAILABLE;
        }

    }


}
