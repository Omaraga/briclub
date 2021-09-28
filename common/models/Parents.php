<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parents".
 *
 * @property int $id
 * @property int $user_id
 * @property int $parent_id
 * @property int $level
 * @property int $time
 */
class Parents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_id', 'level', 'time'], 'integer'],
            [['level'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setChildren($user_id,$parent_id,$level=1){
        if(!empty($parent_id)){
            $parent = new Parents();
            $parent->user_id = $user_id;
            $parent->parent_id = $parent_id;
            $parent->level = $level;
            $parent->time = time();
            $parent->save();
            $parent_db = User::findOne($parent_id);
            if(!empty($parent_db)){
                if(!empty($parent_db['parent_id'])){
                    self::setChildren($user_id, $parent_db['parent_id'],$level+1);
                }
            }
        }
    }
    public static function setChildren2($user_id,$parent_id,$level=1){
        if(!empty($parent_id)){
            $parent = new Parents();
            $parent->user_id = $user_id;
            $parent->parent_id = $parent_id;
            $parent->level = $level;
            $parent->time = 1592172300;
            $parent->save();
            $parent_db = User::findOne($parent_id);
            if(!empty($parent_db)){
                if(!empty($parent_db['parent_id'])){
                    self::setChildren2($user_id, $parent_db['parent_id'],$level+1);
                }
            }
        }
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'time' => 'Time',
        ];
    }
}
