<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "screens".
 *
 * @property int $id
 * @property string $title
 * @property int $fixed
 * @property int $type
 */
class Screens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'screens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fixed', 'type'], 'integer'],
            [['type'], 'required'],
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
            'fixed' => 'Fixed',
            'type' => 'Type',
        ];
    }
}
