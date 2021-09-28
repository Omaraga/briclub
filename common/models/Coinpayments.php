<?php

namespace common\models;

use Yii;
use common\models\User;
use common\models\Actions;
use common\models\ActionTypes;
/**
 * This is the model class for table "coinpayments".
 *
 * @property int $id
 * @property int $entered_amount
 * @property int $amount
 * @property int $from_currency
 * @property int $to_currency
 * @property string $status
 * @property int $gateway_id
 * @property int $gateway_url
 * @property int $user_id
 * @property int $is_payed
 */
class Coinpayments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coinpayments';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entered_amount' => 'Entered Amount',
            'amount' => 'Amount',
            'from_currency' => 'From Currency',
            'to_currency' => 'To Currency',
            'status' => 'Status',
            'gateway_id' => 'Gateway ID',
            'gateway_url' => 'Gateway Url',
            'user_id' => 'User ID',
        ];
    }
    public function successPayment(){
        if ($this->is_payed == 0){
            $user = User::findOne($this->user_id);
            $user->w_balans = $user->w_balans + $this->entered_amount;
            $this->is_payed = 1;
            $action = new Actions();
            $action->type = ActionTypes::POPOLNENIE_COINPAYMENT;
            $action->status = 1;
            $action->time = time();
            $action->sum = $this->entered_amount;
            $action->user_id = $user['id'];
            $action->title = "Пополнение через Coinpayment на сумму ".$this->entered_amount." CV (".$this->amount." ".$this->to_currency.")";

            if ($user->save() && $this->save() && $action->save()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
