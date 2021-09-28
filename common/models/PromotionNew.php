<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "promotion".
 *
 * @property int $id
 * @property string $title
 * @property int $start
 * @property int $end
 * @property int $status
 */
class PromotionNew extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_new';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start', 'end', 'status'], 'integer'],
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
            'title' => 'Название',
            'start' => 'Начало',
            'end' => 'Конец',
            'status' => 'Статус',
        ];
    }
}
