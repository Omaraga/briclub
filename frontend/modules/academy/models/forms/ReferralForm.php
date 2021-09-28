<?php
namespace frontend\modules\academy\models\forms;
use common\models\Acts;
use kartik\mpdf\Pdf;
use yii\base\Model;
use common\models\User;
use Yii;
class ReferralForm extends Model
{
    /**
     * @var $referralName string
     * @var $referral User
     * @var $isReferral integer
     */
    const SCENARIO_ONLINE = 'buy_online';
    const SCENARIO_MLM = 'buy_mlm';
    const SCENARIO_MLM_WITHOUT_REF = 'buy_mlm_without_ref';
    public $referralName;
    public $referral;
    public $isReferral;


    public function scenarios()
    {
        return [
            self::SCENARIO_MLM => ['referralName', 'isReferral',],
            self::SCENARIO_ONLINE => [],
            self::SCENARIO_MLM_WITHOUT_REF => ['isReferral'],
        ];
    }

    /**
     * @return string[][]
     */
    public function rules()
    {
        return [
            ['referralName', 'required', 'on'=>self::SCENARIO_MLM, 'message'=>'Введите логин реферала.'],
            ['isReferral', 'safe'],
            ['referralName', 'checkReferral', 'on'=>self::SCENARIO_MLM],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkReferral($attribute, $params){
        $this->referral = User::find()->where(['username' => $this->referralName])->one();
        if (!$this->referral){
            $this->addError($attribute,  'Реферал не найден!');
        }else if ($this->referral->activ !=1){
            $this->addError($attribute,  'Реферал не активирован!');
        }
    }


    public static function getStylesheetPdf(){
        return "
                    .cr-content{
                overflow: hidden;
                height: 100%;
                width: 100%;
                background-image: url(\"/img/akt.jpg\");
                background-size: contain;
                position: relative;
            }
            .cr-name{
                padding-top: 635px!important;
                padding-left: 130px!important;
                //display: inline-block;
                //text-align: center;
                font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                font-weight: lighter;
                color: #265883;
                font-size: 20px;
            }
            .cr-id{
                padding-top: 150px!important;
                text-align: center;
                font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                font-weight: lighter;
                color: #265883;
                display: block;
                font-size: 20px;
            }
            .cr-sum{
                padding-top: 55px!important;
                padding-left: 580px!important;
                font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                font-weight: lighter;
                color: #265883;
                display: block;
                font-size: 16px;
            }
            .cr-per{
                padding-top: 15px!important;
                padding-left: 330px!important;
                font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                font-weight: lighter;
                color: #265883;
                display: block;
                font-size: 12px;
            }
            .cr-date{
                padding-top: 180px!important;
                padding-left: 339px!important;
                font-family: Calibri, Candara, Segoe, \"Segoe UI\", Optima, Arial, sans-serif;
                font-weight: lighter;
                color: #265883;
                display: block;
                font-size: 12px;
            }
        ";
    }


}