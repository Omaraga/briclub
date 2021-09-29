<?php

namespace common\models;

use kartik\mpdf\Pdf;
use Mpdf\Tag\P;
use common\models\PremiumEvent;
use Yii;

/**
 * This is the model class for table "matrix_ref".
 *
 * @property int $id
 * @property int $user_id
 * @property int $platform_id
 * @property int $parent_id
 * @property int $big_parent_id
 * @property int $children
 * @property int $slots
 * @property int $shoulder1
 * @property int $shoulder2
 * @property int $shoulder1_1
 * @property int $shoulder1_2
 * @property int $shoulder2_1
 * @property int $shoulder2_2
 * @property int $refs
 * @property int $reinvest
 * @property int $clone
 * @property int $deleted
 * @property int $time
 * @property int $buy
 */
class MatrixRef extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'matrix_ref';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'platform_id', 'parent_id', 'big_parent_id', 'children', 'slots', 'shoulder1', 'shoulder2', 'shoulder1_1', 'shoulder1_2', 'shoulder2_1', 'shoulder2_2', 'refs', 'reinvest', 'clone', 'deleted', 'time', 'buy'], 'integer'],
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
            'platform_id' => 'Platform ID',
            'parent_id' => 'Parent ID',
            'big_parent_id' => 'Big Parent ID',
            'children' => 'Children',
            'slots' => 'Slots',
            'shoulder1' => 'Shoulder1',
            'shoulder2' => 'Shoulder2',
            'shoulder1_1' => 'Shoulder1 1',
            'shoulder1_2' => 'Shoulder1 2',
            'shoulder2_1' => 'Shoulder2 1',
            'shoulder2_2' => 'Shoulder2 2',
            'refs' => 'Refs',
            'reinvest' => 'Reinvest',
            'clone' => 'Clone',
            'deleted' => 'Deleted',
            'time' => 'Time',
            'buy' => 'Buy',
        ];
    }
    public static function plusToRefMatrix($user_id,$level=1,$new=false,$reinvest=0,$buy=false,$clon_num=null,$without=null){

        $deleted = false;
        $user = User::findOne($user_id);


        $matrix = new MatrixRef(); // Создаем новую запись в таблице матрицы
        $matrix->user_id = $user_id;
        $matrix->platform_id = $level;
        $matrix->reinvest = $reinvest;
        $matrix->time = time();
        $matrix->deleted = 1;
        if($buy){
            $matrix->buy = 1;
        }
        $matrix->save();
        $matrix = MatrixRef::findOne($matrix->id);

        if($new){
            $user->time_personal = time();
            if(!empty($user['parent_id'])){
                Referals::setParents($user['id'],'personal');
            }
        }
        $action = new Actions();
        if($without == null){
            $last_num = 0;
            if(!empty($clon_num)){
                $mat_clon = new MatClons();
                $mat_clon->user_id = $user['id'];
                $mat_clon->mat_id = $matrix['id'];
                $mat_clon->num = $clon_num;
                $mat_clon->save();
            }else{
                $last_clon = MatClons::find()->where(['user_id'=>$user['id']])->orderBy('num desc')->one();
                if(!empty($last_clon)){
                    $last_num = $last_clon['num'];
                }
                $mat_clon = new MatClons();
                $mat_clon->user_id = $user['id'];
                $mat_clon->mat_id = $matrix['id'];
                $mat_clon->num = $last_num+1;
                $mat_clon->save();
            }
        }




        $action->time = time();
        if($new){
            $action->sum = 5000;
            $action->type = 1;
            $action->title = "Вы купили место [".($matrix->id)."] на уровне ".($matrix->platform_id);
        }else{
            if($buy == true){
                $action->type = 10;
                $action->sum = MLevelsNew::findOne($level)['price'];
                $action->title = "Вы купили место [".($matrix->id)."] на уровне ".($matrix->platform_id);
            }else{
                $action->type = 9;
                $action->title = "Вы активировали место [".($matrix->id)."] на уровне ".($matrix->platform_id);
            }

        }

        $action->status = 1;

        if($reinvest == 1){
            $action->type = 11;
            $action->title = "У вас новый клон [".($matrix->id)."] на уровне ".($matrix->platform_id);
        }

        $action->user_id = $user_id;
        $action->save();

        $matrix->refs = Referals::find()->where(['parent_id'=>$matrix['user_id'],'level'=>1])->count();
        $parent = Referals::find()->where(['user_id'=>$user_id,'level'=>1])->one();



        $mat = \common\models\MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>$level])->orderBy('id asc')->one();

        if(!empty($mat)){
            if($mat['id'] != $matrix['id']){
                $parent_matrix = self::getLastChild($mat['id'],$level);
            }else{
                if(!empty($parent)){// есть спонсор
                    $user_parents = Referals::find()->where(['user_id'=>$user_id])->orderBy('level asc')->all();

                    foreach ($user_parents as $user_parent) {
                        $p_user = User::findOne($user_parent['parent_id']);
                        if(!empty($p_user)){
                            if($p_user['newmatrix'] == 1){
                                $mat = \common\models\MatrixRef::find()->where(['user_id'=>$user_parent['parent_id'],'platform_id'=>$level])->orderBy('id asc')->one();
                                if(!empty($mat)){
                                    $parent_matrix = self::getLastChild($mat['id'],$level);
                                }
                                if(!empty($parent_matrix)){
                                    break;
                                }
                            }
                        }
                    }

                }
            }
        }else{
            if(!empty($parent)){// есть спонсор
                $mat = \common\models\MatrixRef::find()->where(['user_id'=>$parent['parent_id'],'platform_id'=>$level])->orderBy('id asc')->one();
                if(!empty($mat)){
                    $parent_matrix = self::getLastChild($mat['id'],$level);
                }
            }
        }


        if(!empty($parent_matrix)){

            $parent_matrix = MatrixRef::findOne($parent_matrix['id']);

            if($parent_matrix['children'] >1){
                echo "Ошибка!";
                echo $parent_matrix['children'];
                echo "<br>";
            }else{
                if($parent_matrix->children == 0){
                    $parent_matrix->children = 1;
                    $parent_matrix->shoulder1 = $matrix->id;
                    $mat_shoulder = 1;
                }elseif ($parent_matrix->children == 1){
                    $parent_matrix->children = 2;
                    $parent_matrix->shoulder2 = $matrix->id;
                    $mat_shoulder = 2;
                    $pm_user = User::findOne($parent_matrix['user_id']);

                    if($parent_matrix->slots == 1){
                        $shoulder = null;
                        if(!empty($parent_matrix->shoulder1_1)){
                            $shoulder1_1 = MatrixRef::findOne($parent_matrix->shoulder1_1);
                            $shoulder = User::findOne($shoulder1_1['user_id']);
                        }elseif(!empty($parent_matrix->shoulder1_2)){
                            $shoulder1_2 = MatrixRef::findOne($parent_matrix->shoulder1_2);
                            $shoulder = User::findOne($shoulder1_2['user_id']);
                        }elseif(!empty($parent_matrix->shoulder2_1)){
                            $shoulder2_1 = MatrixRef::findOne($parent_matrix->shoulder2_1);
                            $shoulder = User::findOne($shoulder2_1['user_id']);
                        }elseif(!empty($parent_matrix->shoulder2_2)){
                            $shoulder2_2 = MatrixRef::findOne($parent_matrix->shoulder2_2);
                            $shoulder = User::findOne($shoulder2_2['user_id']);
                        }
                    }
                    if($parent_matrix->slots == 2){
                        $shoulder1 = null;
                        $shoulder2 = null;
                        $i = 0;
                        if(!empty($parent_matrix->shoulder1_1)){
                            $i++;
                            $shoulder1_1 = MatrixRef::findOne($parent_matrix->shoulder1_1);
                            $shoulder1 = User::findOne($shoulder1_1['user_id']);
                        }
                        if(!empty($parent_matrix->shoulder1_2)){
                            $shoulder1_2 = MatrixRef::findOne($parent_matrix->shoulder1_2);
                            if($i == 0){
                                $i++;
                                $shoulder1 = User::findOne($shoulder1_2['user_id']);
                            }else{
                                $shoulder2 = User::findOne($shoulder1_2['user_id']);
                            }
                        }
                        if(!empty($parent_matrix->shoulder2_1)){
                            $shoulder2_1 = MatrixRef::findOne($parent_matrix->shoulder2_1);
                            if($i == 0){
                                $i++;
                                $shoulder1 = User::findOne($shoulder2_1['user_id']);
                            }else{
                                $shoulder2 = User::findOne($shoulder2_1['user_id']);
                            }
                        }
                        if(!empty($parent_matrix->shoulder2_2)){
                            $shoulder2_2 = MatrixRef::findOne($parent_matrix->shoulder2_2);
                            if($i == 0){
                                $i++;
                                $shoulder1 = User::findOne($shoulder2_2['user_id']);
                            }else{
                                $shoulder2 = User::findOne($shoulder2_2['user_id']);
                            }
                        }

                    }

                    $pm_user->save();
                    $shoulder_main = MatrixRef::findOne($parent_matrix->shoulder1_1);

                }
            }
            $parent_matrix->save();
            $matrix->parent_id = $parent_matrix->id;
            self::setParents($matrix['id'],$parent_matrix['id'],1,$mat_shoulder);
            $big_parent_matrix = MatrixRef::findOne($parent_matrix->parent_id);
            if(!empty($big_parent_matrix)){
                $bp_user = User::findOne($big_parent_matrix['user_id']);
                if($big_parent_matrix->shoulder1 == $parent_matrix['id']){
                    if($parent_matrix->shoulder1 == $matrix->id){
                        $big_parent_matrix->shoulder1_1 = $matrix->id;
                    }else{
                        $big_parent_matrix->shoulder1_2 = $matrix->id;
                    }
                }else{
                    if($parent_matrix->shoulder1 == $matrix->id){
                        $big_parent_matrix->shoulder2_1 = $matrix->id;
                    }else{
                        $big_parent_matrix->shoulder2_2 = $matrix->id;
                    }
                }
                $slots = 0;
                if(!empty($big_parent_matrix->shoulder1_1)){
                    $slots = $slots+1;
                }
                if(!empty($big_parent_matrix->shoulder1_2)){
                    $slots = $slots+1;
                }
                if(!empty($big_parent_matrix->shoulder2_1)){
                    $slots = $slots+1;
                }
                if(!empty($big_parent_matrix->shoulder2_2)){
                    $slots = $slots+1;
                }
                $big_parent_matrix->slots = $slots;

                $big_parent_matrix->save();
                $matrix->big_parent_id = $big_parent_matrix->id;

                if($big_parent_matrix->slots == 4){
                    if ($level == 1){
                        User::plusBalans($bp_user['id'],2500);
                        User::plusBri($bp_user['id'],750);
                        User::plusGrc($bp_user['id'],100);

                        $action_bon = new Actions();
                        $action_bon->time = time();
                        $action_bon->status = 1;
                        $action_bon->sum = 2500;
                        $action_bon->user_id = $big_parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username']."[".$matrix['id']."]";
                        $action_bon->type = 7;
                        $action_bon->save();

                        /* После теста включить
                        $can = new Canplatforms();
                        $can->user_id = $bp_user['id'];
                        $can->mat_id = $big_parent_matrix['id'];
                        $can->platform = $level+1;
                        $can->save();
                        */
                        self::plusToRefMatrix($bp_user['id'],$level+1,false,0,false,null,1);
                    }else if($level == 2){
                        User::plusBalans($bp_user['id'],7500);
                        User::plusBri($bp_user['id'],2500);
                        User::plusGrc($bp_user['id'],250);
                        $action_bon = new Actions();
                        $action_bon->time = time();
                        $action_bon->status = 1;
                        $action_bon->sum = 7500;
                        $action_bon->user_id = $big_parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username']."[".$matrix['id']."]";
                        $action_bon->type = 7;
                        $action_bon->save();

                        /* После теста включить
                        $can = new Canplatforms();
                        $can->user_id = $bp_user['id'];
                        $can->mat_id = $big_parent_matrix['id'];
                        $can->platform = $level+1;
                        $can->save();
                        */
                        self::plusToRefMatrix($bp_user['id'],$level+1,false,0,false,null,1);

                    }else if($level == 3){
                        User::plusBalans($bp_user['id'],90000);
                        User::plusBri($bp_user['id'],16750);
                        User::plusGrc($bp_user['id'],1150);

                        $dohodCompany = new DohodCompany();
                        $dohodCompany->user_id = $bp_user['id'];
                        $dohodCompany->sum = 45000;
                        $dohodCompany->save(false);

                        $action_bon = new Actions();
                        $action_bon->time = time();
                        $action_bon->status = 1;
                        $action_bon->sum = 90000;
                        $action_bon->user_id = $big_parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username']."[".$matrix['id']."]";
                        $action_bon->type = 7;
                        $action_bon->save();


                    }
                }
                $bp_user->save();
            }

        }else{
            echo "Нет парента";
            echo "<br>";
        }
        if(!$deleted){
            $matrix->deleted = 2;
            $matrix->save();
            $parent_block = Matblocks::find()->where(['mat_id'=>$matrix['parent_id']])->all();
            if(!empty($parent_block)){
                foreach ($parent_block as $item) {
                    $block = new Matblocks();
                    $block->mat_id = $matrix->id;
                    $block->user_id = $item['user_id'];
                    $block->save();
                }
            }
        }

        if($user){
            $user->newmatrix = 1;
            $user->activ = 1;
            $user->save();
        }
    }

    public static function getPriorityChild($user_id,$level=1){
        $parent_matrix1 = MatrixRef::find()->where(['user_id'=>$user_id,'platform_id'=>$level])->orderBy('id asc')->one();

        if(!empty($parent_matrix1)){
            if($parent_matrix1['children']>1){ // если у спонсора 2 плеча
                $parent_matrix1 = MatrixRef::find()->where(['parent_id'=>$parent_matrix1['id'],'platform_id'=>$level,'deleted'=>2])->andWhere(['<','children',2])->orderBy('id asc')->one();
            }
        }
        $parent_children_levels = Referals::find()->where(['parent_id'=>$user_id])->orderBy('level desc')->one()['level'];
        for($i = 1;$i<$parent_children_levels+1;$i++){
            $parent_matrix_children = Referals::find()->where(['parent_id'=>$user_id,'level'=>$i])->orderBy('id asc')->all();
            foreach ($parent_matrix_children as $parent_matrix_child) {
                $parent_matrix = MatrixRef::find()->where(['user_id'=>$parent_matrix_child['user_id'],'platform_id'=>$level,'deleted'=>2])->orderBy('reinvest asc')->one();

                if(!empty($parent_matrix)){
                    if($parent_matrix['children']>1){ // если у спонсора 2 плеча
                        $parent_matrix = MatrixRef::find()->where(['parent_id'=>$parent_matrix['id'],'platform_id'=>$level,'deleted'=>2])->andWhere(['<','children',2])->orderBy('id asc')->one();
                        break;
                    }
                }
            }

            if(!empty($parent_matrix)){
                break;
            }
        }
        if(!empty($parent_matrix)){
            if(!empty($parent_matrix1)){
                if($parent_matrix['id']<$parent_matrix1['id']){
                    return $parent_matrix;
                }else{
                    return $parent_matrix1;
                }
            }else{
                return $parent_matrix;
            }
        }else{
            return $parent_matrix1;
        }


    }

    public static function getLastChild2($mat_id,$level=1){
        $parent_matrix = MatrixRef::findOne($mat_id);
        $user = Yii::$app->user->identity;
        $block = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$mat_id])->one();
        if(!empty($block)){
            return null;
        }
        if(!empty($parent_matrix)){
            if($parent_matrix['children']>1){ // если у спонсора 2 плеча

                $s1 = MatrixRef::findOne($parent_matrix['shoulder1']);
                $s2 = MatrixRef::findOne($parent_matrix['shoulder2']);

                $block1 = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$parent_matrix['shoulder1']])->one();
                $block2 = Matblocks::find()->where(['user_id'=>$user['id'],'mat_id'=>$parent_matrix['shoulder2']])->one();

                if($s1['children'] < 2 and empty($block1)){
                    return $s1;
                }elseif($s2['children'] <2 and empty($block2)){
                    return $s2;
                }elseif ($s1['children'] >1 and empty($block1)){
                    return $parent_matrix = self::getLastChild($parent_matrix['shoulder1'],$level);
                }elseif ($s2['children'] >1 and empty($block2)){
                    return $parent_matrix = self::getLastChild($parent_matrix['shoulder2'],$level);
                }

            }else{
                return $parent_matrix;
            }

        }
        return null;

    }
    public static function pwr($a, $b) {
        $count = count($b);
        $amount = pow($count, $a);
        $array = [];
        $arr = [];

        for ($x=0; $x < $a; $x++) {
            $i = -1;
            while (@count($array[$x]) <> $amount) {
                for ($y = 0; $y < $count; $y++) {
                    for($r = 0; $r < (pow($count, $x)); $r++) {
                        $i++;
                        $array[$x][$i] = $b[$y];
                    }
                }
            }
        }

        $u = 0;
        for ($k = 0; $k < $amount; $k++) {
            $u++;
            for ($x = 0; $x < $a; $x++) {
                $arr[$u][$x]=$array[$x][$k];
            }
        }
        $arrres = [];
        foreach ($arr as $item) {
            $arrres[] = (implode('',$item))*1;
        }

        sort($arrres);
        return $arrres;
    }
    public static function getLastChildOld($mat_id,$level=1){

        $parent_matrix = MatrixRef::findOne($mat_id);
        $user = Yii::$app->user->identity;
        $mat_user = User::findOne($parent_matrix['user_id']);
        $block = Matblocks::find()->where(['user_id'=>$mat_user['id'],'mat_id'=>$mat_id])->one();
        if(!empty($block)){
            return null;
        }
        if(!empty($parent_matrix)){
            if($parent_matrix['children']>1){ // если у спонсора 2 плеча
                $max_level = MatParents::find()->where(['parent_id'=>$mat_id])->orderBy('level desc')->one()['level'];
                for($i=1;$i<=$max_level+2;$i++){

                    $refs = MatParents::find()->where(['parent_id'=>$mat_id,'level'=>$i])->all();
                    $count = pow(2,$i);
                    //if(count($refs) < $count){
                        $n = [1, 2];
                        $line = $i;
                        $list = self::pwr($line,$n);



                        foreach ($list as $item) {

                            $parent_matrix = self::getForParent($mat_id,$item);

                            $block2 = Matblocks::find()->where(['user_id'=>$mat_user['id'],'mat_id'=>$parent_matrix['id']])->one();

                            if(!empty($parent_matrix) and $parent_matrix['children']<2 and empty($block2)){

                                return $parent_matrix;
                            }
                        }
                    //}
                }
            }else{
                return $parent_matrix;
            }
        }
        return null;
    }
    public static function  getForParent($mat_id,$algs){
        $algs = str_split($algs);
        $matrix = MatrixRef::findOne($mat_id);
        foreach ($algs as $alg) {
            $shoulder = 'shoulder'.$alg;
            $matrix = MatrixRef::findOne($matrix[$shoulder]);
        }
        return $matrix;
    }

    public static function getParents($mat_id,$level=1){
        $user = User::findOne(Yii::$app->user->id);
        $main = MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>$level])->orderBy('id asc')->one();
        $parents = array();
        $matrix = MatrixRef::findOne($mat_id);
        if(!empty($matrix['parent_id']) and $mat_id != $main['id']){
            for ($i=0;$i<1000;$i++){
                $parent = self::getParent($mat_id,$level);

                if(!empty($parent)){
                    $parents[] = $parent;
                    $mat_id = $parent['id'];
                    if($parent['id'] == $main['id']){
                        break;
                    }
                }
            }
        }
        return $parents;
    }


    public static function getParent($mat_id,$level=1){

        $matrix = MatrixRef::findOne($mat_id);
        $parent = MatrixRef::findOne($matrix['parent_id']);

        if(!empty($parent)){
            return $parent;
        }
    }

    public static function getNum($mat_id){

        $matrix = MatrixRef::findOne($mat_id);
        $mats = MatrixRef::find()->where(['user_id'=>$matrix['user_id']])->orderBy('id asc')->all();
        $i = 0;
        foreach ($mats as $mat) {
            $i++;
            if($matrix['id'] == $mat['id']){
                break;
            }
        }
        return $i;
    }

    public static function plusToSystem($user_id){
        $user = User::findOne($user_id);
        if(!empty($user['parent_id'])){
            $parent = User::findOne($user['parent_id']);
            $children_p = User::find()->where(['parent_id'=>$parent['id']])->andWhere(['>=','level',$parent['level']])->andWhere(['>','platform_id',0])->andWhere(['not in','id',$user_id])->all();
            if(count($children_p) == 1){
                $parent->level = $parent->level + 1;
                $parent->save();
                self::plusToSystem($parent->id);
                if($parent['level'] == 5 and $parent['platform_id']<3){
                    self::plusToMatrix($parent['id'],3);
                }
            }
        }
    }
    public static function getLastChild($mat_id,$level=1){
        $parent_matrix = MatrixRef::findOne($mat_id);
        $q_list = [];
        array_push($q_list, $parent_matrix);
        $mat_user = User::findOne($parent_matrix['user_id']);
        $matBlocks = Matblocks::find()->select(['mat_id'])->where(['user_id'=>$mat_user['id']])->all();
        $userMatBlockList = [];
        foreach ($matBlocks as $item){
            array_push($userMatBlockList, $item['mat_id']);
        }
        while (sizeof($q_list) > 0){
            $mat_ref = array_shift($q_list);
            if(in_array($mat_ref['id'], $userMatBlockList)){
                continue;
            }
            if ($mat_ref['children'] > 1){
                $left_shoulder = MatrixRef::findOne($mat_ref['shoulder1']);
                $right_shoulder = MatrixRef::findOne($mat_ref['shoulder2']);
                if (isset($left_shoulder) && !empty($left_shoulder)){
                    array_push($q_list, $left_shoulder);
                }
                if (isset($right_shoulder) && !empty($right_shoulder)){
                    array_push($q_list, $right_shoulder);
                }
            }else{
                return $mat_ref;
            }
        }
    }
    public static function setParents($mat_id,$parent_id,$level,$shoulder){

        $mat_parent = new MatParents();
        $mat_parent->mat_id = $mat_id;
        $mat_parent->parent_id = $parent_id;
        $mat_parent->time = time();
        $mat_parent->level = $level;
        $mat_parent->shoulder = $shoulder;
        $mat_parent->order = $shoulder;
        $mat_parent->save();


        $parent_parents = MatParents::find()->where(['mat_id'=>$parent_id])->all();
        foreach ($parent_parents as $parent_parent) {
            $mat_parent = new MatParents();
            $mat_parent->mat_id = $mat_id;
            $mat_parent->parent_id = $parent_parent['parent_id'];
            $mat_parent->time = time();
            $mat_parent->level = $parent_parent['level']+1;
            $mat_parent->shoulder = $parent_parent['shoulder'];

            $mat_parent->order = $parent_parent['order'];
            $mat_parent->save();

            /*$n = [1, 2];
            $line = $parent_parent['level']+1;
            $list = \common\models\MatrixRef::pwr($line,$n);

            if($line>3){
                $k = 1;
                foreach ($list as $item) {
                    $ch_mat = \common\models\MatrixRef::getForParent($parent_parent['parent_id'],$item);
                    if(!empty($ch_mat) and $ch_mat['id']==$mat_id){

                        $mat_parent->order = $k;
                        $mat_parent->save();
                    }
                    $k++;
                }
            }*/

        }
    }
}
