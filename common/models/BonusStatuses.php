<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bonus_statuses".
 *
 * @property int $id
 * @property string $title
 * @property string $color
 */
class BonusStatuses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bonus_statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'color'], 'string', 'max' => 255],
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
            'color' => 'Color',
        ];
    }
}
