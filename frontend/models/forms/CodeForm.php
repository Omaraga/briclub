<?php
namespace frontend\models\forms;

use app\modules\users\models;
use yii\base\Model;
use Yii;

class CodeForm extends Model
{
    public $code;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code' => Yii::t('users', 'Код'),
        ];
    }

}
