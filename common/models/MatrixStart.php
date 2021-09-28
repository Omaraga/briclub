<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "matrix_start".
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
class MatrixStart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'matrix_start';
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

    public static function plusToRefMatrix($user_id,$level=1,$new=false,$reinvest=false,$buy=false){
        if(!$new and !$buy){
            $old_level = $level;
            if($reinvest == 0){
                $old_level = $level-1;
            }
            $old_matrix = MatrixStart::find()->where(['user_id'=>$user_id,'platform_id'=>$old_level,'reinvest'=>$reinvest,'deleted'=>2])->one();
            $old_matrix = MatrixStart::findOne($old_matrix['id']);
            if(!empty($old_matrix)){
                $old_matrix_children = MatrixStart::find()->where(['parent_id'=>$old_matrix['id'],'deleted'=>2])->all();
                foreach ($old_matrix_children as $old_matrix_child) {
                    $old_matrix_child = MatrixStart::findOne($old_matrix_child['id']);
                    $old_matrix_child->parent_id = null;
                    $old_matrix_child->big_parent_id = null;
                    $old_matrix_child->save();
                }
                $old_matrix->deleted = 1;
                $old_matrix->save();
            }
        }

        $user = User::findOne($user_id);
        if($new){
            $user->time_start = time();
            if(!empty($user['parent_id'])){
                Referals::setParents($user['id'],'start');
            }
        }

        $matrix = new MatrixStart(); // Создаем новую запись в таблице матрицы
        $matrix->user_id = $user_id;
        $matrix->platform_id = $level;
        $matrix->reinvest = $reinvest;
        $matrix->time = time();
        $matrix->deleted = 1;
        if($buy){
            $matrix->buy = 1;
        }
        $matrix->save();
        $matrix = self::findOne($matrix->id);
        $action = new Actions();
        $action->time = time();
        $action->type = 11;
        $action->status = 1;
        $m_level = MLevelsStart::findOne($matrix->platform_id);
        if($matrix->platform_id == 1){
            $action->sum = 15;
        }else{
            $action->sum = $m_level['price'];
        }


        $action->title = "Вы перешли на уровень ".($matrix->platform_id);


        $action->user_id = $user_id;
        $action->save();

        $matrix->refs = Referals::find()->where(['parent_id'=>$matrix['user_id'],'level'=>1])->count();
        $parent = Referals::find()->where(['user_id'=>$user_id,'level'=>1])->one();
        $parent_matrix = null;
        if(!empty($parent)){// есть спонсор

            $user_parents = Referals::find()->where(['user_id'=>$user_id])->orderBy('level asc')->all();

            foreach ($user_parents as $user_parent) {
                $p_user = User::findOne($user_parent['parent_id']);
                if(!empty($p_user)){
                    if($p_user['start'] == 1){
                        $parent_matrix = self::getPriorityChild($user_parent['parent_id'],$level);
                        if(!empty($parent_matrix)){
                            break;
                        }
                    }
                }

            }
        }

        if(!empty($parent_matrix)){
            $parent_matrix = MatrixStart::findOne($parent_matrix['id']);
            if($parent_matrix['children'] >1){
                echo "Ошибка!";
                echo $parent_matrix['children'];
                echo "<br>";
            }else{
                $pm_user = User::findOne($parent_matrix['user_id']);
                $m_level = MLevelsStart::findOne($matrix->platform_id);
                if($parent_matrix->children == 0){
                    $parent_matrix->children = 1;
                    $parent_matrix->shoulder1 = $matrix->id;
                    if($parent_matrix->reinvest == 0 and $parent_matrix->platform_id <4){
                        User::plusBalans($pm_user['id'],$m_level['line1']);
                        $action_bon = new Actions();
                        $action_bon->time = time();
                        $action_bon->status = 1;
                        $action_bon->sum = $m_level['line1'];
                        $action_bon->user_id = $parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$parent_matrix->platform_id." за пользователя ".$user['username'];
                        $action_bon->type = 77;
                        $action_bon->save();
                    }
                }elseif ($parent_matrix->children == 1){
                    $parent_matrix->children = 2;
                    $parent_matrix->shoulder2 = $matrix->id;
                    if($parent_matrix->reinvest == 0 and $parent_matrix->platform_id <4){
                        User::plusBalans($pm_user['id'],$m_level['line1']);
                        $action_bon = new Actions();
                        $action_bon->time = time();
                        $action_bon->status = 1;
                        $action_bon->sum = $m_level['line1'];
                        $action_bon->user_id = $parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$parent_matrix->platform_id." за пользователя ".$user['username'];
                        $action_bon->type = 77;
                        $action_bon->save();
                    }


                    if($parent_matrix->slots == 1){
                            $action_bon = new Actions();
                            if($parent_matrix->reinvest == 1){
                                User::plusBalans($pm_user['id'],33);
                                $action_bon->sum = 33;
                            }else{
                                if($parent_matrix->platform_id == 4){
                                    User::plusBalans($pm_user['id'],100);
                                    $action_bon->sum = 100;
                                }else{
                                    User::plusBalans($pm_user['id'],$m_level['line2']);
                                    $action_bon->sum = $m_level['line2'];
                                }

                            }
                            $shoulder = null;
                            if(!empty($parent_matrix->shoulder1_1)){
                                $shoulder1_1 = MatrixStart::findOne($parent_matrix->shoulder1_1);
                                $shoulder = User::findOne($shoulder1_1['user_id']);
                            }elseif(!empty($parent_matrix->shoulder1_2)){
                                $shoulder1_2 = MatrixStart::findOne($parent_matrix->shoulder1_2);
                                $shoulder = User::findOne($shoulder1_2['user_id']);
                            }elseif(!empty($parent_matrix->shoulder2_1)){
                                $shoulder2_1 = MatrixStart::findOne($parent_matrix->shoulder2_1);
                                $shoulder = User::findOne($shoulder2_1['user_id']);
                            }elseif(!empty($parent_matrix->shoulder2_2)){
                                $shoulder2_2 = MatrixStart::findOne($parent_matrix->shoulder2_2);
                                $shoulder = User::findOne($shoulder2_2['user_id']);
                            }

                            $action_bon->time = time();
                            $action_bon->status = 1;

                            $action_bon->user_id = $parent_matrix->user_id;
                            $action_bon->user2_id = $shoulder['id'];
                            $action_bon->title = "Начислены бонусы за место на площадке ".$parent_matrix->platform_id." за пользователя ".$shoulder['username'];
                            $action_bon->type = 77;
                            $action_bon->save();

                    }
                    if($parent_matrix->slots == 2){
                        $action_bon = new Actions();
                        if($parent_matrix->reinvest == 1){
                            User::plusBalans($pm_user['id'],33);
                            $action_bon->sum = 33;
                        }else{
                            if($parent_matrix->platform_id == 4){
                                User::plusBalans($pm_user['id'],200);
                                $action_bon->sum = 200;
                            }else{
                                User::plusBalans($pm_user['id'],$m_level['line2']*2);
                                $action_bon->sum = $m_level['line2']*2;
                            }

                        }
                        $shoulder1 = null;
                        $shoulder2 = null;
                        $i = 0;
                        if(!empty($parent_matrix->shoulder1_1)){
                            $i++;
                            $shoulder1_1 = MatrixStart::findOne($parent_matrix->shoulder1_1);
                            $shoulder1 = User::findOne($shoulder1_1['user_id']);
                        }
                        if(!empty($parent_matrix->shoulder1_2)){
                            $shoulder1_2 = MatrixStart::findOne($parent_matrix->shoulder1_2);
                            if($i == 0){
                                $i++;
                                $shoulder1 = User::findOne($shoulder1_2['user_id']);
                            }else{
                                $shoulder2 = User::findOne($shoulder1_2['user_id']);
                            }
                        }
                        if(!empty($parent_matrix->shoulder2_1)){
                            $shoulder2_1 = MatrixStart::findOne($parent_matrix->shoulder2_1);
                            if($i == 0){
                                $i++;
                                $shoulder1 = User::findOne($shoulder2_1['user_id']);
                            }else{
                                $shoulder2 = User::findOne($shoulder2_1['user_id']);
                            }
                        }
                        if(!empty($parent_matrix->shoulder2_2)){
                            $shoulder2_2 = MatrixStart::findOne($parent_matrix->shoulder2_2);
                            if($i == 0){
                                $i++;
                                $shoulder1 = User::findOne($shoulder2_2['user_id']);
                            }else{
                                $shoulder2 = User::findOne($shoulder2_2['user_id']);
                            }
                        }

                        $action_bon->time = time();
                        $action_bon->status = 1;

                        $action_bon->user_id = $parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        if(!empty($shoulder1) and !empty($shoulder2)){
                            $action_bon->title = "Начислены бонусы за место на площадке ".$parent_matrix->platform_id." за пользователей ".$shoulder1['username']." и ".$shoulder2['username'];
                        }else{
                            $action_bon->title = "Начислены бонусы за место на площадке ".$parent_matrix->platform_id." за пользователя ".$user['username'];
                        }

                        $action_bon->type = 77;
                        $action_bon->save();

                    }
                }
                $pm_user->save();
            }
            $parent_matrix->save();
            $matrix->parent_id = $parent_matrix->id;
            $big_parent_matrix = MatrixStart::findOne($parent_matrix->parent_id);
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

                if($big_parent_matrix->children>1){
                    if($big_parent_matrix->slots == 1){
                        $m_level = MLevelsStart::findOne($matrix->platform_id);
                        $action_bon = new Actions();
                        if($big_parent_matrix->reinvest == 1){
                            User::plusBalans($bp_user['id'],33);
                            $action_bon->sum = 33;
                        }else{
                            if($big_parent_matrix->platform_id == 4){
                                User::plusBalans($bp_user['id'],100);
                                $action_bon->sum = 100;
                            }else{
                                User::plusBalans($bp_user['id'],$m_level['line2']);
                                $action_bon->sum = $m_level['line2'];
                            }

                        }


                        $action_bon->time = time();
                        $action_bon->status = 1;

                        $action_bon->user_id = $big_parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username'];
                        $action_bon->type = 77;
                        $action_bon->save();
                    }
                    if($big_parent_matrix->slots == 2){
                        $m_level = MLevelsStart::findOne($matrix->platform_id);
                        $action_bon = new Actions();
                        if($big_parent_matrix->reinvest == 1 ){
                            User::plusBalans($bp_user['id'],33);
                            $action_bon->sum = 33;
                        }else{
                            if($big_parent_matrix->platform_id == 4){
                                User::plusBalans($bp_user['id'],100);
                                $action_bon->sum = 100;
                            }else{
                                User::plusBalans($bp_user['id'],$m_level['line2']);
                                $action_bon->sum = $m_level['line2'];
                            }

                        }

                        $action_bon->time = time();
                        $action_bon->status = 1;
                        $action_bon->user_id = $big_parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username'];
                        $action_bon->type = 77;
                        $action_bon->save();

                    }
                }
                if($big_parent_matrix->slots == 3){
                    $m_level = MLevelsStart::findOne($matrix->platform_id);
                    if($big_parent_matrix->platform_id !=4){
                        $action_bon = new Actions();
                        if($big_parent_matrix->reinvest == 1){
                            User::plusBalans($bp_user['id'],33);
                            $action_bon->sum = 33;
                        }else{
                            User::plusBalans($bp_user['id'],$m_level['line2']);
                            $action_bon->sum = $m_level['line2'];
                        }

                        $action_bon->time = time();
                        $action_bon->status = 1;

                        $action_bon->user_id = $big_parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username'];
                        $action_bon->type = 77;
                        $action_bon->save();
                    }


                    if($big_parent_matrix->platform_id>3 and $big_parent_matrix->reinvest == 0){
                        self::plusToRefMatrix($big_parent_matrix['user_id'],$big_parent_matrix->platform_id-1,false,1);
                    }

                }
                if($big_parent_matrix->slots == 4){

                    $m_level = MLevelsStart::findOne($matrix->platform_id);
                    if($big_parent_matrix->reinvest == 0 and $big_parent_matrix->platform_id < 4){
                        User::plusBalans($bp_user['id'],$m_level['line2']);
                        $action_bon = new Actions();
                        $action_bon->time = time();
                        $action_bon->status = 1;
                        $action_bon->sum = $m_level['line2'];
                        $action_bon->user_id = $big_parent_matrix->user_id;
                        $action_bon->user2_id = $user['id'];
                        $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username'];
                        $action_bon->type = 77;
                        $action_bon->save();
                    }


                    if($big_parent_matrix->reinvest == 1){
                        self::plusToRefMatrix($big_parent_matrix['user_id'],$big_parent_matrix->platform_id,false,1);
                    }else{
                        $big_parent_matrix->deleted = 1;
                        $big_parent_matrix->save();
                    }

                    if($big_parent_matrix->platform_id == 4 and $big_parent_matrix->reinvest == 0){
                        $bp_mat_ref = MatrixRef::find()->where(['user_id'=>$big_parent_matrix['user_id']])->one();
                        if(!empty($bp_mat_ref)){
                            User::plusBalans($bp_user['id'],75);
                            $action_bon = new Actions();
                            $action_bon->time = time();
                            $action_bon->status = 1;
                            $action_bon->sum = 75;
                            $action_bon->user_id = $big_parent_matrix->user_id;
                            $action_bon->user2_id = $user['id'];
                            $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username'];
                            $action_bon->type = 77;
                            $action_bon->save();
                        }else{
                            MatrixRef::plusToRefMatrix($big_parent_matrix['user_id'],1,true,0);
                        }
                        $bp_mat_global = UserPlatforms::find()->where(['user_id'=>$big_parent_matrix['user_id']])->one();
                        if(!empty($bp_mat_global)){
                            User::plusBalans($bp_user['id'],75);
                            $action_bon = new Actions();
                            $action_bon->time = time();
                            $action_bon->status = 1;
                            $action_bon->sum = 75;
                            $action_bon->user_id = $big_parent_matrix->user_id;
                            $action_bon->user2_id = $user['id'];
                            $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent_matrix->platform_id." за пользователя ".$user['username'];
                            $action_bon->type = 77;
                            $action_bon->save();
                        }else{
                            UserPlatforms::plusToMatrix($big_parent_matrix['user_id'],1,true);
                        }
                    }

                }
                $bp_user->save();
            }

        }

        $matrix->deleted = 2;
        $matrix->save();
        if($user){
            $user->platform_id = $matrix->platform_id;
            $user->activ = 1;
            $user->start = 1;
            $user->save();
        }
    }

    public static function getPriorityChild($user_id,$level=1){
        $parent_matrix = MatrixStart::find()->where(['user_id'=>$user_id,'platform_id'=>$level,'deleted'=>2])->orderBy('reinvest asc')->one();
        if(!empty($parent_matrix)){
            if($parent_matrix['children']>1){ // если у спонсора 2 плеча
                $parent_matrix = MatrixStart::find()->where(['parent_id'=>$parent_matrix['id'],'platform_id'=>$level,'deleted'=>2])->andWhere(['<','children',2])->orderBy('id asc')->one();
            }
        }else{
            $parent_children_levels = Referals::find()->where(['parent_id'=>$user_id])->orderBy('level desc')->one()['level'];
            for($i = 1;$i<$parent_children_levels+1;$i++){
                $parent_matrix_children = Referals::find()->where(['parent_id'=>$user_id,'level'=>$i])->orderBy('id asc')->all();
                foreach ($parent_matrix_children as $parent_matrix_child) {
                    $parent_matrix = MatrixStart::find()->where(['user_id'=>$parent_matrix_child['user_id'],'platform_id'=>$level,'deleted'=>2])->orderBy('reinvest asc')->one();
                    if(!empty($parent_matrix)){
                        if($parent_matrix['children']>1){ // если у спонсора 2 плеча
                            $parent_matrix = MatrixStart::find()->where(['parent_id'=>$parent_matrix['id'],'platform_id'=>$level,'deleted'=>2])->andWhere(['<','children',2])->orderBy('id asc')->one();
                            break;
                        }
                    }
                }

                if(!empty($parent_matrix)){
                    break;
                }
            }
        }

        return $parent_matrix;
    }
}
