<?php
namespace frontend\modules\advcash\models;

use Yii;
use common\models\Deposit;
use common\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для записи в базу пополнений средств.
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $sum
 * status
 * response
 * salt
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdvcashDeposit extends Deposit
{
    const ACTIVITY_TYPE = 44;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deposits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_PENDING],
            [['user_id', 'sum', 'status'], 'required'],
            [['response', 'success_response', 'failure_response', 'payment_id', 'payment_date', 'status', 'salt'], 'string'],
            [['user_id'], 'integer'],
            [['sum'], 'number', 'numberPattern' => '/^\d+(\.\d{1,8})?$/']
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'sum' => "Сумма",
            'status' => "Статус",
            'response' => "Ответ системы",
            'created_at' => "Создано",
            'updated_at' => "Изменено",
        ];
    }

    /**
     * Перед сохранением указываем какой тип вывода
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
     
            return true;
        }
        return false;
    }

    
}
