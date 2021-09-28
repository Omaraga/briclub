<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data2".
 *
 * @property int $id
 * @property int $order
 * @property string $username
 * @property string $parent
 * @property string $fio
 * @property string $email
 * @property string $tel
 * @property string $firstname
 * @property string $lastname
 * @property string $secondname
 * @property int $user_id
 */
class Data2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order', 'user_id'], 'integer'],
            [['username', 'parent', 'fio', 'email', 'tel', 'firstname', 'lastname', 'secondname'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order' => 'Order',
            'username' => 'Username',
            'parent' => 'Parent',
            'fio' => 'Fio',
            'email' => 'Email',
            'tel' => 'Tel',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'secondname' => 'Secondname',
            'user_id' => 'User ID',
        ];
    }
}
