<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mat_parents".
 *
 * @property int $id
 * @property int $mat_id
 * @property int $parent_id
 * @property int $level
 * @property string $time
 * @property int $shoulder
 * @property int $order
 */
class MatParents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mat_parents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mat_id', 'parent_id', 'level', 'shoulder', 'time' ,'order'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mat_id' => 'Mat ID',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'time' => 'Time',
            'order' => 'Order',
            'shoulder' => 'Shoulder',
        ];
    }
    public static function getParents($mat_id){
        $user = Yii::$app->user->identity;
        $matrix = MatrixRef::findOne($mat_id);
        $main = MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>$matrix['platform_id']])->orderBy('id asc')->one();

        $pars = array();
        if($main['id'] != $mat_id){
            $parents = \common\models\MatParents::find()->where(['mat_id'=>$mat_id])->orderBy('level asc')->all();
            foreach ($parents as $parent) {
                $pars[] = $parent;
                if($parent['parent_id'] == $main['id']){
                    break;
                }
            }
        }

        return array_reverse($pars);
    }
}
