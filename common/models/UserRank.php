<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_rank".
 *
 * @property int $id
 * @property string $title
 * @property int $position
 * @property string $fund
 * @property string $dividends
 */
class UserRank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_rank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position'], 'integer'],
            [['fund', 'dividends'], 'number'],
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
            'position' => 'Position',
            'fund' => 'Fund',
            'dividends' => 'Dividends',
        ];
    }

    public static function setRank(User $user){
        $upMatrix = MatrixRef::find()->where(['user_id' => $user->id])->orderBy('platform_id DESC')->one();
        if ($upMatrix){
            if ($upMatrix->platform_id == 1){
                $user->rank_id = 1;
            }else if($upMatrix->platform_id == 2){
                $user->rank_id = 2;
            }else if($upMatrix->platform_id == 3){
                if($upMatrix->slots == 4){
                    $user->rank_id = 4;
                }else{
                    $user->rank_id = 3;
                }
            }
            $user->save();
        }
    }
}
