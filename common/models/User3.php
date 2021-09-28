<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user3".
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
 * @property int $w_balans
 * @property string $firstname
 * @property string $lastname
 * @property string $secondname
 * @property int $country_id
 */
class User3 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user3';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'email', 'created_at', 'updated_at'], 'required'],
            [['confirm', 'status', 'created_at', 'updated_at', 'order', 'parent_id', 'platform_id', 'balans', 'w_balans', 'country_id'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'fio', 'phone', 'firstname', 'lastname', 'secondname'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'fio' => 'Fio',
            'phone' => 'Phone',
            'confirm' => 'Confirm',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'order' => 'Order',
            'parent_id' => 'Parent ID',
            'platform_id' => 'Platform ID',
            'balans' => 'Balans',
            'w_balans' => 'W Balans',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'secondname' => 'Secondname',
            'country_id' => 'Country ID',
        ];
    }
}
