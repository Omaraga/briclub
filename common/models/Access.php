<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "access".
 *
 * @property int $id
 * @property string $username
 * @property int $user_id
 * @property int $superadmin
 */
class Access extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'superadmin','admin','accountant','moderator'], 'integer'],
            [['username'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'superadmin' => 'Superadmin',
        ];
    }
}
