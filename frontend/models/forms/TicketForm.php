<?php

namespace frontend\models\forms;

use common\models\Tokens;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property int $type
 * @property string $title
 * @property int $max_length
 * @property int $link
 * @property int $screen_course_id
 *
 * @property ContentTypes $type0
 * @property CourseScreens $screenCourse
 */
class TicketForm extends Model
{
    public $file;
    public $text;
    public $title;
    public $category;
    public $tokens;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text','title','category'], 'required'],
            [['text'], 'string'],
            [['file'], 'file', 'extensions' => ['png', 'jpg', 'pdf', 'doc', 'xls', 'docx', 'xlsx', 'jpeg'], 'maxSize' => 1024 * 1024 * 3],
            //['tokens', 'checkTokens'],
        ];
    }

    /*public function checkTokens($attribute, $params)
    {
        $user = Yii::$app->user->identity;
        $user_tokens = Tokens::findOne(['user_id'=>$user['id']]);
        if(!empty($user_tokens)){
            $balans = $user_tokens->balans;
            if($balans < $this->tokens){
                $this->addError($attribute,  'Недостаточно токенов!');
            }
        }else{
            $this->addError($attribute,  'Недостаточно токенов!');
        }
    }*/
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Текст',
            'title' => 'Тема',
            'file' => 'Файл',
            'category' => 'Раздел',
            'tokens' => 'Комиссия',
        ];
    }

}
