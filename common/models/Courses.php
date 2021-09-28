<?php

namespace common\models;

use common\models\UserCourses;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "courses".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_active
 * @property int $old_price
 * @property int $price
 * @property string $duration
 * @property int $type
 * @property string $certificate
 * @property int $soon
 * @property int $icon_url
 * @property int $preview_url
 * @property int $icon
 * @property int $icon_color
 * @property int $description
 */
class Courses extends \yii\db\ActiveRecord
{
    CONST ONLINE_COURSE = 1;
    CONST MLM = 2;
    CONST INVEST = 3;
    CONST CRIPTO = 4;
    public $icon;
    public $preview;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'is_active', 'old_price', 'price', 'type', 'soon' ,'start', 'global', 'personal'], 'integer'],
            [['title', 'duration', 'icon_color', 'preview_url'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['icon', 'preview'], 'file'],
            [['alias', 'certificate'], 'string', 'max' => 510],
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
            'alias' => 'Алиас',
            'created_at' => 'Создано в',
            'updated_at' => 'Выберите мероприятие',
            'is_active' => 'Статус',
            'old_price' => 'Старая цена',
            'price' => 'Цена',
            'icon_color' =>'Цвет иконки',
            'duration' => 'Длительность',
            'description' => 'Описание',
            'type' => 'Тип',
            'certificate' => 'Сертификат',
            'soon' => 'Скоро',
        ];
    }

    /**
     * @param $user_id
     * @param $couse_id
     */

    public static function setAccess($user_id,$couse_id){
        $user_course = UserCourses::find()->where(['user_id'=>$user_id,'course_id'=>$couse_id])->one();
        if(empty($user_course)){
            $user_course = new UserCourses();
            $user_course->user_id = $user_id;
            $user_course->course_id = $couse_id;
            $user_course->date = time();
            $user_course->save();
        }
    }

    /**
     * @return array|Parts|\yii\db\ActiveQuery|\yii\db\ActiveRecord|null
     */
    public function getIntroPart(){
        if($part = Parts::find()->where(['course_id'=>$this->id])->andWhere(['is_intro'=>1])->one()){
            return $part;
        }else{
            return null;
        }

    }

    /**
     * @return array|Courses|\yii\db\ActiveRecord|null
     */
    public static function getMlm(){
        return Courses::find()->where(['type' => 2])->one();
    }

    /**
     * @return string
     */
    public function getPrice(){
        if ($this->soon == 1){
            return '<i class="fa fa-clock-o" aria-hidden="true"></i> Скоро';
        }else if($this->price > 0){
            return 'от CV '.$this->price;
        }else{
            return '<span style="color:forestgreen">Бесплатно</span>';
        }
    }

    /**
     * @return array
     */
    public static function getCoursesAsMap(){
        $courses = Courses::find()->all();
        return ArrayHelper::map($courses, 'id', 'title');
    }

    /**
     * @param $id
     * @return string
     */
    public static function getCourseTitle($id){
        $course = Courses::findOne($id);
        if ($course){
            return $course->title;
        }else{
            return '';
        }
    }

    /**
     * @param User $user
     * @return array|Courses|false|\yii\db\ActiveRecord|null
     */
    public static function getActualCourse(User $user){

        $mlm = self::getMlm();
        if ($mlm && UserCourses::isAccess($user->id, $mlm->id) && $user->activ == 1){
            return $mlm;
        }else{
            $userCourse = UserCourses::find()->where(['user_id'=>$user->id])->one();
            if ($userCourse){
                return Courses::findOne($userCourse->course_id);
            }
        }
        return false;
    }

    /**
     * @param $user
     * @return int[]
     */
    public function getCourseProgress($user){
        $progress = ['totalModule' => 0, 'userModule' => 0, 'totalLesson' => 0, 'userLesson' => 0];
        if ($this->type == self::MLM){
            $personalMatrix = \common\models\MatrixRef::find()->where(['user_id'=>$user['id']])->orderBy('platform_id desc')->one();
            $upperMatrix = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'], 'platform_id' => $personalMatrix->platform_id])->orderBy('id asc')->one();
            $progress['totalModule'] = 6;
            $progress['userModule'] = $personalMatrix->platform_id;
            $progress['totalLesson'] = 7;
            $progress['userLesson'] = $upperMatrix->children + $upperMatrix->slots + 1;
        }else if($this->type == self::ONLINE_COURSE){
            $modules = Parts::find()->where(['course_id' => $this->id])->andWhere(['!=', 'is_intro', 1])->orderBy('position ASC')->all();
            $modulesList = [];
            $i = 1;
            foreach ($modules as $module){
                //0 - userLesson, 1 - module position,
                $modulesList[$module->id] = [0 , $i];
                $i++;
            }
            $userLessons = \common\models\UserLessons::find()->where(['user_id'=>$user->id,'course_id'=>$this->id])->all();
            $upperModule = 0;
            $upperModuleId = 0;
            foreach ($userLessons as $item) {
                $lessonDb = \common\models\Lessons::findOne($item['lesson_id']);
                if ($lessonDb && key_exists($lessonDb->part_id, $modulesList)){
                    $modulesList[$lessonDb->part_id][0]++;
                    if ($modulesList[$lessonDb->part_id][1] > $upperModule){
                        $upperModule = $modulesList[$lessonDb->part_id][1];
                        $upperModuleId = $lessonDb->part_id;
                    }
                }
            }
            if (!$userLessons){
                $upperModuleId = Parts::find()->where(['course_id' => $this->id])->andWhere(['!=', 'is_intro', 1])->orderBy('position DESC')->one()->id;
            }
            $progress['totalModule'] = sizeof($modules);
            $progress['userModule'] = $upperModule;
            $progress['totalLesson'] = Lessons::find()->where(['course_id' => $this->id, 'part_id' => $upperModuleId])->count();
            $progress['userLesson'] = ($upperModuleId && key_exists($upperModuleId, $modulesList))?$modulesList[$upperModuleId][0]:0;
        }
        return $progress;
    }

    /**
     * @param $user User
     * @return array|\common\models\UserCourses|false|\yii\db\ActiveRecord|null
     */
    public function activateCourse(User $user){
        $userCourse = UserCourses::find()->where(['user_id' => $user->id, 'course_id' => $this->id])->one();
        if (!$userCourse){
            $userCourseModel = new UserCourses();
            $userCourseModel->user_id = $user->id;
            $userCourseModel->course_id = $this->id;
            $userCourseModel->date = time();
            $userCourseModel->save();
            return $userCourse;
        }else{
            return false;
        }
    }

    /**
     * @return int|string
     */
    public function getIcon(){
        if ($this->icon_url){
            return $this->icon_url;
        }else{
            return '/img/academy/white-logo.svg';
        }
    }


    public function getPreview(){
        if ($this->preview_url){
            return $this->preview_url;
        }else{
            return '/img/academy/Rectangle 163.png';
        }

    }





}
