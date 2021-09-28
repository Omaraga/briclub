<?php
namespace common\models;

use Yii;
use app\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 *
 */
class Deposit extends ActiveRecord
{
    const STATUS_PENDING = 'P';
    const STATUS_COMPLETED = 'S';
    const STATUS_ERROR = 'E';
    const STATUS_CANCELED = 'C';

    public $statuses = array('P' => 'Ожидает', 'S' => 'Успешно проведен', 'E' => 'Ошибка', 'C' => 'Отменен');
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
            'id' => Yii::t('app/payments', 'ID'),
            'user_id' => Yii::t('app/payments', 'User ID'),
            'sum' => Yii::t('app/payments', 'Sum'),
            'status' => Yii::t('app/payments', 'Status'),
            'response' => Yii::t('app/payments', 'Response'),
            'created_at' => Yii::t('app/payments', 'Created At'),
            'updated_at' => Yii::t('app/payments', 'Updated At'),
        ];
    }

    public static function getAll()
    {
        return static::find();
    }

    public static function getById($id)
    {
        return static::findOne(['id' => $id]);
    }

    protected function getStatusName()
    {
	    return (isset($this->status)) ? $this->statuses[$this->status] : 'Не установлен';
    }

    public function getDepositName()
    {
        $type = DepositType::getById($this->deposit_type);
        return ($type != null) ? $type->name : '';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
