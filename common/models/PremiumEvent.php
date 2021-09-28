<?php

namespace common\models;

use common\models\logic\PremiumsManager;
use common\models\MatrixRef;
use Yii;

/**
 * This is the model class for table "premium_event".
 *
 * @property int $id
 * @property int $user_id
 * @property string $price
 * @property int $end_time
 */
class PremiumEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'premium_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'end_time'], 'integer'],
            [['price'], 'number'],
            [['end_time'], 'required'],
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
            'price' => 'Price',
            'end_time' => 'End Time',
        ];
    }

    public static function create($user_id){
        $premiumEvent = PremiumEvent::find()->where(['user_id' => $user_id])->one();
        $premium = Premiums::find()->where(['user_id' => $user_id])->one();
        if ($premium && ($premium->time + $premium->expires_at > time())){
            return 0;
        }
        $upperMatrix = MatrixRef::find()->where(['user_id' => $user_id])->orderBy('platform_id DESC')->one();
        if ($upperMatrix && $upperMatrix->platform_id > 1){
            return 0;
        }
        if (!$premiumEvent){
            $premiumEvent = new PremiumEvent();
            $premiumEvent->user_id = $user_id;
            $premiumEvent->price = 100;
            $premiumEvent->end_time = time() + (4*24*60*60);
            $premiumEvent->save();
            PremiumsManager::addPremium(7, $user_id);
        }
    }
}
