<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $title
 * @property int $code
 * @property int $country_id
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'code', 'country_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'code' => 'Code',
            'country_id' => 'Country ID',
        ];
    }
}
