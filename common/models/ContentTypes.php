<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "content_types".
 *
 * @property int $id
 * @property string $title
 * @property int $screen_id
 * @property int $count
 *
 * @property Content[] $contents
 * @property Screens $screen
 */
class ContentTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['screen_id', 'count','group_id',], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['screen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Screens::className(), 'targetAttribute' => ['screen_id' => 'id']],
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
            'count' => 'Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Content::className(), ['type' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScreen()
    {
        return $this->hasOne(Screens::className(), ['id' => 'screen_id']);
    }
}
