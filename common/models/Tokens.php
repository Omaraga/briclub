<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tokens".
 *
 * @property int $id
 * @property int $user_id
 * @property string $balans
 */
class Tokens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['balans'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'balans' => 'Balans',
        ];
    }
    public static function checkTokenBalans($user_id){
        $types_minus = ActionTypes::find()->where(['cat'=>5,'minus'=>1])->all();
        $minus_types = [];
        foreach ($types_minus as $types_min) {
            $minus_types[] = $types_min['id'];
        }
        $types_plus = ActionTypes::find()->where(['cat'=>5,'minus'=>2])->all();
        $plus_types = [];
        foreach ($types_plus as $types_pl) {
            $plus_types[] = $types_pl['id'];
        }
        $balans = \common\models\Tokens::find()->where(['user_id'=>$user_id])->sum('balans');

        $pluses = \common\models\Actions::find()->where(['type'=>$plus_types,'user_id'=>$user_id])->sum('tokens');
        $minuses = \common\models\Actions::find()->where(['type'=>$minus_types,'user_id'=>$user_id])->sum('tokens');

        if($balans>($pluses-$minuses)){
            return "Баланс больше чем должен на ".($balans-($pluses-$minuses));
        }elseif ($balans<($pluses-$minuses)){
            return "Баланс меньше чем должен на ".(($pluses-$minuses)-$balans);
        }else{
            return "Баланс правильный";
        }
    }
}
