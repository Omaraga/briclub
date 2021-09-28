<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "changes".
 *
 * @property int $id
 * @property string $title
 * @property string $cur1
 * @property string $cur2
 */
class Changes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'changes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cur1', 'cur2'], 'number'],
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
            'cur1' => 'Cur1',
            'cur2' => 'Cur2',
        ];
    }
}
