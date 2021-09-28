<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $fio
 * @property string $phone
 * @property int $confirm
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $order
 * @property int $parent_id
 * @property int $platform_id
 * @property int $balans
 * @property string $w_balans
 * @property string $firstname
 * @property string $lastname
 * @property string $secondname
 * @property int $country_id
 * @property int $level
 * @property string $last_ip
 * @property int $newmatrix
 * @property int $minus_balans
 * @property int $activ
 * @property int $global
 * @property int $start
 * @property int $vacant
 * @property int $time_start
 * @property int $time_personal
 * @property int $time_global
 * @property int $block
 * @property string $overdraft
 * @property int $overbinar
 * @property int $verification
 * @property int $canplatform
 * @property string $b_balans
 * @property string $limit
 *
 * @property UsersList $parent
 * @property UsersList[] $usersLists
 * @property Countries $country
 */
class UsersList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'email', 'created_at', 'updated_at'], 'required'],
            [['confirm', 'status', 'created_at', 'updated_at', 'order', 'parent_id', 'platform_id', 'balans', 'country_id', 'level', 'newmatrix', 'minus_balans', 'activ', 'global', 'start', 'vacant', 'time_start', 'time_personal', 'time_global', 'block', 'overbinar', 'verification', 'canplatform'], 'integer'],
            [['w_balans', 'overdraft', 'b_balans', 'limit'], 'number'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'fio', 'phone', 'firstname', 'lastname', 'secondname', 'last_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersList::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'confirm' => 'Confirm',
            'status' => 'Status',
            'created_at' => 'Дата регистрации',
            'regDateFrom' => 'Дата регистрации С',
            'regDateTo' => 'Дата регистрации По',
            'updated_at' => 'Updated At',
            'order' => 'Order',
            'parent_id' => 'Parent ID',
            'platform_id' => 'Platform ID',
            'platform' => 'Площадка',
            'balans' => 'Balans',
            'w_balans' => 'Баланс',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'secondname' => 'Secondname',
            'country_id' => 'Страна',
            'structure' => 'Структура пользователя',
            'level' => 'Level',
            'last_ip' => 'Last Ip',
            'newmatrix' => 'Newmatrix',
            'minus_balans' => 'Minus Balans',
            'activ' => 'Активный',
            'global' => 'Global',
            'start' => 'Start',
            'vacant' => 'Vacant',
            'time_start' => 'Time Start',
            'time_personal' => 'Дата активации',
            'time_global' => 'Time Global',
            'block' => 'Block',
            'overdraft' => 'Overdraft',
            'overbinar' => 'Overbinar',
            'verification' => 'Verification',
            'canplatform' => 'Canplatform',
            'b_balans' => 'B Balans',
            'limit' => 'Limit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(UsersList::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersLists()
    {
        return $this->hasMany(UsersList::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
    }

    /**
     * {@inheritdoc}
     * @return UsersListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersListQuery(get_called_class());
    }
}
