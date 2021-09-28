<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "content_groups".
 *
 * @property int $id
 * @property string $title
 * @property int $screen_id
 */
class ContentGroups extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'screen_id'], 'required'],
            [['screen_id'], 'integer'],
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
            'screen_id' => 'Screen ID',
        ];
    }
}
