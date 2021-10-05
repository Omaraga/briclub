<?php


namespace frontend\models;
use yii\base\Model;
use common\models\ActionTypes;
class StatisticModel extends Model
{
    public $dohodKurator;
    public $dohodBonusPolz;
    public $dohodPeriod = 0;
    public $childrenPeriodSize = 0;
    public $statisticArray;
    public $childrenDohodKurator;
    public $childrenDohodBonus;
    public $childrenDohod;
    public $lichnikDohodKurator;
    public $lichnikDohodBonus;
    public $lichnikDohod;
    public $childrenSize;
    public $clonesSize;
    public $lichnikSize;
    public $boughtPlaces;
    public function __construct($user, $type, $isShort = false)
    {
        $this->dohodKurator = \common\models\Actions::find()->where(['user_id'=>$user['id']])->andWhere(['type'=> ActionTypes::KURORTNYI_BONUS])->sum('sum');
        $this->dohodBonusPolz = \common\models\Actions::find()->where(['user_id'=>$user['id']])->andWhere(['type'=> ActionTypes::BONUS_ZA_POLZOVATELYA_SHANYRAK])->sum('sum');
        $thisYear = intval(date('y'));
        $thisMonth = intval(date("m"));
        $currDay = intval(date('d'));
        if ($type == 'year'){
            /*Формируем массив для графика*/
            $dateStart = strtotime("1 January ".$thisYear);
            $dateEnd = strtotime("31 December ".$thisYear);
            // двумерный массив [номер_месяцы => [название_месяца, доход, добавлено_людей]]
            $this->statisticArray = [1=>['Янв',0, 0], 2=>['Фев',0, 0], 3 => ['Март', 0, 0], 4=>['Апр', 0, 0],5 => ['Май',0,0],6 => ['Июнь',0,0], 7 => ['Июль',0,0], 8 => ['Авг',0,0], 9=>['Сен',0,0], 10 => ['Окт',0,0], 11 =>['Нояб',0,0], 12=>['Дек',0,0]];
            /*Личный доход*/
            $actions = \common\models\Actions::find()->select(['time','sum'])->where(['user_id'=>$user['id']])->andWhere(['type'=>[ActionTypes::BONUS_ZA_POLZOVATELYA_SHANYRAK, ActionTypes::KURORTNYI_BONUS]])->andWhere(['>=', 'time', $dateStart])->andWhere(['<=', 'time', $dateEnd]) ->all();
            foreach ($actions as $item){
                $index = intval(date('n',$item['time']));
                $this->statisticArray[$index][1] +=$item['sum'];
                $this->dohodPeriod+=$item['sum'];
            }
            /*Статистика матрицы*/
            $myReferrals = \common\models\Referals::find()->select(['time'])->where(['parent_id'=>$user['id']])->andWhere(['>=', 'time', $dateStart])->andWhere(['<=', 'time', $dateEnd]) ->all();
            foreach ($myReferrals as $referral){
                $index = intval(date('n',$referral['time']));
                $this->statisticArray[$index][2]++;
                $this->childrenPeriodSize++;
            }
        }else if($type == 'month'){
            /*Формируем массив для графика*/

            $dateEnd = strtotime('today');
            $dateStart = strtotime("-1 month", $dateEnd);
            // двумерный массив [день.месяц => [день.месяц, доход, добавлено_людей]]
            $this->statisticArray = [];
            for ($i=$dateStart;$i<=$dateEnd;$i = strtotime('+1 day', $i)){
                $this->statisticArray[date('d.m',$i)] = [date('d.m',$i), 0, 0];
            }
            /*Личный доход*/
            $actions = \common\models\Actions::find()->select(['time','sum'])->where(['user_id'=>$user['id']])->andWhere(['type'=>[ActionTypes::BONUS_ZA_POLZOVATELYA_SHANYRAK, ActionTypes::KURORTNYI_BONUS]])->andWhere(['>=', 'time', $dateStart])->andWhere(['<', 'time', $dateEnd]) ->all();
            foreach ($actions as $item){
                $index = date('d.m',$item['time']);
                $this->statisticArray[$index][1] +=$item['sum'];
                $this->dohodPeriod+=$item['sum'];
            }
            /*Статистика матрицы*/
            $myReferrals = \common\models\Referals::find()->select(['time'])->where(['parent_id'=>$user['id']])->andWhere(['>=', 'time', $dateStart])->andWhere(['<=', 'time', $dateEnd]) ->all();
            foreach ($myReferrals as $referral){
                $index = date('d.m',$referral['time']);
                $this->statisticArray[$index][2] ++;
                $this->childrenPeriodSize++;
            }
        }else if ($type == 'week'){
            /*Формируем массив для графика*/
            $dateStart = strtotime('monday this week');
            $dateEnd = strtotime('sunday this week');
            $this->statisticArray = [];
            $weekNames = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
            // двумерный массив [номер_дня_недели => [название_дня_недели, доход, добавлено_людей]]
            for ($i=$dateStart;$i<=$dateEnd;$i = strtotime('+1 day', $i)){
                $this->statisticArray[date('w',$i)] = [$weekNames[intval(date('w',$i))], 0, 0];
            }
            /*Личный доход*/
            $actions = \common\models\Actions::find()->select(['time','sum'])->where(['user_id'=>$user['id']])->andWhere(['type'=>[ActionTypes::BONUS_ZA_POLZOVATELYA_SHANYRAK, ActionTypes::KURORTNYI_BONUS]])->andWhere(['>=', 'time', $dateStart])->andWhere(['<', 'time', $dateEnd]) ->all();
            foreach ($actions as $item){
                $index = date('w',$item['time']);
                $this->statisticArray[$index][1] +=$item['sum'];
                $this->dohodPeriod+=$item['sum'];
            }
            /*Статистика матрицы*/
            $myReferrals = \common\models\Referals::find()->select(['time'])->where(['parent_id'=>$user['id']])->andWhere(['>=', 'time', $dateStart])->andWhere(['<=', 'time', $dateEnd]) ->all();

            foreach ($myReferrals as $referral){
                $index = date('w',$referral['time']);
                $this->statisticArray[$index][2] ++;
                $this->childrenPeriodSize++;
            }
        }
        if ($isShort == false){
            /*Доход структуры*/
            $children = \common\models\Referals::find()->select('user_id')->where(['parent_id'=>$user['id']])->all();
            $mapChildIds = [];
            foreach ($children as $child){
                array_push($mapChildIds, $child['user_id']);
            }
            $this->childrenDohodKurator = \common\models\Actions::find()->where(['user_id'=>$mapChildIds])->andWhere(['type'=> ActionTypes::KURORTNYI_BONUS])->sum('sum');;
            $this->childrenDohodBonus = \common\models\Actions::find()->where(['user_id'=>$mapChildIds])->andWhere(['type'=> ActionTypes::BONUS_ZA_POLZOVATELYA_SHANYRAK])->sum('sum');;
            $this->childrenDohod = $this->childrenDohodBonus + $this->childrenDohodKurator;

            /*Доход личников*/
            $refmat_own = \common\models\Referals::find()->select('user_id')->where(['parent_id'=>$user['id'],'level'=>1,'activ'=>1])->all();
            $mapRefmat = [];
            foreach ($refmat_own as $child){
                array_push($mapRefmat, $child['user_id']);
            }
            $this->lichnikDohodKurator = \common\models\Actions::find()->where(['user_id'=>$mapRefmat])->andWhere(['type'=> ActionTypes::KURORTNYI_BONUS])->sum('sum');;
            $this->lichnikDohodBonus = \common\models\Actions::find()->where(['user_id'=>$mapRefmat])->andWhere(['type'=> ActionTypes::BONUS_ZA_POLZOVATELYA_SHANYRAK])->sum('sum');;
            $this->lichnikDohod = $this->lichnikDohodBonus + $this->lichnikDohodKurator;

            $this->childrenSize = sizeof($children);
            $this->lichnikSize = sizeof($refmat_own);
            $this->clonesSize = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'reinvest'=>1])->count();
            $this->boughtPlaces = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'reinvest'=>0])->count();
        }

    }

    public static function getMoneyFormat($money){
        if ($money > 0){
            return 'PV '.$money;
        }else{
            return '0';
        }
    }
    public static function getProcent($a, $b){
        if($a > 0) {
            return intval($a/$b*100);
        }else{
            return '0';
        }
    }

    public static function getHeight($referals){
        if($referals==0){
            return '0';
        }else if($referals<=10){
            return intval(($referals*4));
        }else if($referals>10 && $referals<=50){
            return intval((30*($referals-10))/40)+40;
        }else if($referals>50 && $referals<=100){
            return intval((30*($referals-50))/50)+70;
        }else{
            return 110;
        }
    }

}