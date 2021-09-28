<?php
namespace common\models\lib;
use common\models\Access;
use common\models\ActionTypes;
use common\models\User;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Transactions extends ActiveRecord
{
    public static function getBalans($user_id)
    {
        $user = User::findOne($user_id);
        if(!empty($user)){
            $acts = \common\models\Actions::find()->where(['user_id'=>$user_id])->all();
            $sum = 0;
            $sum = number_format($sum,2, '.', '');
            foreach ($acts as $act) {
                if($act['type'] == 2) continue;
                $type = ActionTypes::findOne($act['type']);
                if($type['minus'] == 1){
                    $sum = $sum - $act['sum'];
                }else{
                    $sum = $sum + $act['sum'];
                }
            }

            $withs = \common\models\Withdraws::find()->where(['user_id'=>$user_id])->all();
            foreach ($withs as $with) {
                if(!empty($with['sum'])){
                    $sum = $sum - $with['sum'];
                }

            }

            $sum = number_format($sum,2, '.', '');
            return $sum;
        }else{
            return false;
        }

    }

    public static function checkBalans($user_id)
    {
        $user = User::findOne($user_id);
        if(!empty($user)){
            $sum = self::getBalans($user['id']);
            if($sum){
                $balans = $user['w_balans'] + $user['b_balans'];
                $sum = number_format($sum,2, '.', '');
                $balans = number_format($balans,2, '.', '');
                if($sum == $balans){
                    return "Баланс правильный";
                }else{
                    return "Не соответсвие баланса на ".($balans-$sum);
                }
            }
        }else{
            return "Ошибка! Пользователя не существует!";
        }

    }

    public static function clearActs(){
        $acts = \common\models\Actions::find()->where(['type'=>[56,57]])->all();
        foreach ($acts as $act) {
            if($act['sum']>0 and $act['tokens']<=0){
                $act->tokens = $act->sum;
                $act->sum = 0;
                $act->save();
            }
        }
    }

}
