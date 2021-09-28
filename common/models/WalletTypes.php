<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wallet_types".
 *
 * @property int $id
 * @property string $name
 * @property string $comment
 */
class WalletTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallet_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'comment'], 'required'],
            [['name', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'comment' => 'Комментарий',
        ];
    }
}
