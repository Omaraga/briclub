<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_platforms".
 *
 * @property int $id
 * @property int $user_id
 * @property int $platform_id
 * @property int $parent_id
 * @property int $children
 * @property int $slots
 * @property int $deleted
 * @property int $vacant
 * @property int $shoulder1
 * @property int $shoulder2
 * @property int $shoulder1_1
 * @property int $shoulder1_2
 * @property int $shoulder2_1
 * @property int $shoulder2_2
 * @property int $time
 */
class UserPlatforms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_platforms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'platform_id', 'parent_id', 'children', 'slots', 'deleted', 'vacant', 'shoulder1', 'shoulder2', 'shoulder1_1', 'shoulder1_2', 'shoulder2_1', 'shoulder2_2', 'time'], 'integer'],
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
            'children' => 'Children',
            'slots' => 'Slots',
            'deleted' => 'Deleted',
            'vacant' => 'Vacant',
            'shoulder1' => 'Shoulder1',
            'shoulder2' => 'Shoulder2',
            'shoulder1_1' => 'Shoulder1 1',
            'shoulder1_2' => 'Shoulder1 2',
            'shoulder2_1' => 'Shoulder2 1',
            'shoulder2_2' => 'Shoulder2 2',
            'time' => 'Time',
        ];
    }

    public static function plusToMatrix($user_id,$level=1,$new=false){
        $user = User::findOne($user_id);
        if($new){
            $user->time_global = time();
            if(!empty($user['parent_id'])){
                Referals::setParents($user['id'],'global');
            }

        }

        $matrix = UserPlatforms::find()->where(['user_id'=>$user_id,'platform_id'=>$level-1,'deleted'=>2])->one();
        if(!empty($matrix)){
            $current = UserPlatforms::findOne($matrix->id);
            $children = UserPlatforms::find()->where(['parent_id'=>$current->id])->all();
            foreach ($children as $child) {
                $child = UserPlatforms::findOne($child['id']);
                $child->parent_id = null;
                $child->save();
            }
            $current->deleted = 1;
            $current->save();
        }
        $matrix = new UserPlatforms();
        $matrix->time = time();
        $matrix->user_id = $user_id;
        $matrix->platform_id = $level;
        $matrix->deleted = 1;
        $matrix->save();
        $matrix = UserPlatforms::findOne($matrix->id);
        $action = new Actions();
        $action->time = time();
        if($new){
            $action->sum = 75;
            $action->type = 8;
        }else{
            $action->type = 10;
        }
        $action->status = 1;
        $action->title = "Вы перешли на уровень ".($matrix->platform_id-1);
        if($matrix->platform_id == 1){
            $action->title = "Вы в глобальном потоке";
        }

        $action->user_id = $user['id'];
        $action->save();


        if($user){
            $user->global = 1;
            $user->activ = 1;
            $user->save();
        }



        if($matrix->platform_id == 1 and !empty($user['parent_id'])){

            $parent_m = UserPlatforms::find()->where(['user_id'=>$user['parent_id'],'deleted'=>2])->one();
            $parent_m = UserPlatforms::findOne($parent_m['id']);

            if($parent_m['slots']>5 and $parent_m['platform_id'] == 2){
                self::plusToMatrix($parent_m->user_id,$parent_m->platform_id+1);
            }
        }
        if($matrix->platform_id == 3 and !empty($user['parent_id'])){
            $parent_m = UserPlatforms::find()->where(['user_id'=>$user['parent_id'],'deleted'=>2])->one();
            $parent_m = UserPlatforms::findOne($parent_m['id']);
            $parent_child = User::find()->where(['parent_id'=>$parent_m['user_id'],'platform_id'=>4])->one();
            if($parent_m['slots']>5 and $parent_m['platform_id'] == 6 and !empty($parent_child)){
                self::plusToMatrix($parent_m->user_id,$parent_m->platform_id+1);
            }
        }
        if($matrix->platform_id == 4 and !empty($user['parent_id'])){
            $parent_m = UserPlatforms::find()->where(['user_id'=>$user['parent_id'],'deleted'=>2])->one();
            $parent_m = UserPlatforms::findOne($parent_m['id']);
            if($parent_m['platform_id'] == 6){
                $parent_child = User::find()->where(['parent_id'=>$parent_m['user_id'],'platform_id'=>3])->one();
                if($parent_m['slots']>5 and !empty($parent_child)){
                    self::plusToMatrix($parent_m->user_id,$parent_m->platform_id+1);
                }
            }elseif ($parent_m['platform_id'] == 7){
                $parent_child = User::find()->where(['parent_id'=>$parent_m['user_id'],'platform_id'=>5])->one();
                if($parent_m['slots']>5 and !empty($parent_child)){
                    self::plusToMatrix($parent_m->user_id,$parent_m->platform_id+1);
                }
            }

        }

        if($matrix->platform_id == 5 and !empty($user['parent_id'])){
            $parent_m = UserPlatforms::find()->where(['user_id'=>$user['parent_id'],'deleted'=>2])->one();
            $parent_m = UserPlatforms::findOne($parent_m['id']);
            if($parent_m['platform_id'] == 7){
                $parent_child = User::find()->where(['parent_id'=>$parent_m['user_id'],'platform_id'=>4])->one();
                if($parent_m['slots']>5 and !empty($parent_child)){
                    self::plusToMatrix($parent_m->user_id,$parent_m->platform_id+1);
                }
            }elseif ($parent_m['platform_id'] == 8){
                $parent_child = User::find()->where(['parent_id'=>$parent_m['user_id'],'platform_id'=>6])->one();
                if($parent_m['slots']>5 and !empty($parent_child)){
                    self::plusToMatrix($parent_m->user_id,6);
                }
            }

        }
        if($matrix->platform_id == 6 and !empty($user['parent_id'])){
            $parent_m = UserPlatforms::find()->where(['user_id'=>$user['parent_id'],'deleted'=>2])->one();
            $parent_m = UserPlatforms::findOne($parent_m['id']);
            if($parent_m['platform_id'] == 8){
                $parent_child = User::find()->where(['parent_id'=>$parent_m['user_id'],'platform_id'=>5])->one();
                if($parent_m['slots']>5 and !empty($parent_child)){
                    self::plusToMatrix($parent_m->user_id,6);
                }
            }

        }


        $last_parent = UserPlatforms::find()->where(['platform_id'=>$matrix->platform_id,'deleted'=>2])->andWhere(['<','children',2])->orderBy('id asc')->one();
        if(!empty($last_parent)){

            $last_parent = UserPlatforms::findOne($last_parent['id']);
            $matrix->parent_id = $last_parent['id'];
            $matrix->deleted = 2;
            $matrix->save();
            if($last_parent->platform_id == 1){
                if($last_parent->children == 0){
                    $last_parent->children = 1;
                    $last_parent->shoulder1 = $matrix->user_id;
                }elseif ($last_parent->children == 1){
                    $last_parent->children = 2;
                    $last_parent->shoulder2 = $matrix->user_id;
                    self::plusToMatrix($last_parent->user_id,$last_parent->platform_id+1);
                }

                $last_parent->slots = $last_parent->slots + 1;
                $last_parent->save();
            }else{
                $lp_user = User::findOne($last_parent->user_id);
                if($last_parent->children == 0){
                    $last_parent->children = 1;
                    $last_parent->shoulder1 = $matrix->user_id;
                    $m_level = MLevels::findOne($matrix->platform_id);
                    User::plusBalans($lp_user['id'],$m_level['line1']);
                    $action_bon = new Actions();
                    $action_bon->time = time();
                    $action_bon->status = 1;
                    $action_bon->sum = $m_level['line1'];
                    $action_bon->user_id = $lp_user['id'];
                    $action_bon->user2_id = $user['id'];
                    $action_bon->title = "Начислены бонусы за место на площадке ".$last_parent->platform_id." за пользователя ".$user['username'];
                    $action_bon->type = 88;
                    $action_bon->save();
                    $lp_user->save();
                }elseif ($last_parent->children == 1){
                    $last_parent->children = 2;
                    $last_parent->shoulder2 = $matrix->user_id;
                    $m_level = MLevels::findOne($matrix->platform_id);
                    User::plusBalans($lp_user['id'],$m_level['line1']);
                    $action_bon = new Actions();
                    $action_bon->time = time();
                    $action_bon->status = 1;
                    $action_bon->sum = $m_level['line1'];
                    $action_bon->user_id = $lp_user['id'];
                    $action_bon->user2_id = $user['id'];
                    $action_bon->title = "Начислены бонусы за место на площадке ".$last_parent->platform_id." за пользователя ".$user['username'];
                    $action_bon->type = 88;
                    $action_bon->save();
                    $lp_user->save();
                }

                $last_parent->slots = $last_parent->slots + 1;
                $last_parent->save();

                $big_parent = UserPlatforms::findOne($last_parent->parent_id);
                if(!empty($big_parent)){
                    $big_parent->slots = $big_parent->slots +1;
                    $bp_user = User::findOne($big_parent['user_id']);
                    if($big_parent->slots == 3){
                        $big_parent->shoulder1_1 = $matrix->user_id;
                    }elseif($big_parent->slots == 4){
                        $big_parent->shoulder1_2 = $matrix->user_id;
                    }elseif($big_parent->slots == 5){
                        $big_parent->shoulder2_1 = $matrix->user_id;
                    }elseif($big_parent->slots == 6){
                        $big_parent->shoulder2_2 = $matrix->user_id;
                    }
                    $m_level = MLevels::findOne($matrix->platform_id);
                    User::plusBalans($bp_user['id'],$m_level['line2']);
                    $action_bon = new Actions();
                    $action_bon->time = time();
                    $action_bon->status = 1;
                    $action_bon->sum = $m_level['line2'];
                    $action_bon->user_id = $bp_user['id'];
                    $action_bon->user2_id = $user['id'];
                    $action_bon->title = "Начислены бонусы за место на площадке ".$big_parent->platform_id." за пользователя ".$user['username'];
                    $action_bon->type = 88;
                    $action_bon->save();
                    $bp_user->save();
                    $big_parent->save();
                    if($big_parent->slots>5){

                        if($big_parent->platform_id == 2){
                            $bp_children = User::find()->where(['parent_id'=>$bp_user['id'],'activ'=>1])->all();
                            if(count($bp_children)>1){
                                self::plusToMatrix($big_parent->user_id,$big_parent->platform_id+1);
                            }
                        }elseif($big_parent->platform_id == 6){
                            $if1 = false;
                            $if2 = false;
                            $bp_children = User::find()->where(['parent_id'=>$bp_user['id'],'activ'=>1])->all();
                            foreach ($bp_children as $bp_child) {
                                $child_mat = UserPlatforms::find()->where(['user_id'=>$bp_child['id'],'platform_id'=>3])->one();
                                if(!empty($child_mat)){
                                    $if1 = true;
                                }
                                $child_mat2 = UserPlatforms::find()->where(['user_id'=>$bp_child['id'],'platform_id'=>4])->one();
                                if(!empty($child_mat2)){
                                    $if2 = true;
                                }
                            }
                            if($if1 and $if2){
                                self::plusToMatrix($big_parent->user_id,$big_parent->platform_id+1);
                            }

                        }elseif($big_parent->platform_id == 7){
                            $bp_children = User::find()->where(['parent_id'=>$bp_user['id'],'activ'=>1])->all();
                            foreach ($bp_children as $bp_child) {
                                $child_mat = UserPlatforms::find()->where(['user_id'=>$bp_child['id'],'platform_id'=>4])->one();
                                if(!empty($child_mat)){
                                    $if1 = true;
                                }
                                $child_mat2 = UserPlatforms::find()->where(['user_id'=>$bp_child['id'],'platform_id'=>5])->one();
                                if(!empty($child_mat2)){
                                    $if2 = true;
                                }
                            }
                            if($if1 and $if2){
                                self::plusToMatrix($big_parent->user_id,$big_parent->platform_id+1);
                            }
                        }elseif($big_parent->platform_id == 8){
                            $bp_children = User::find()->where(['parent_id'=>$bp_user['id'],'activ'=>1])->all();
                            foreach ($bp_children as $bp_child) {
                                $child_mat = UserPlatforms::find()->where(['user_id'=>$bp_child['id'],'platform_id'=>5])->one();
                                if(!empty($child_mat)){
                                    $if1 = true;
                                }
                                $child_mat2 = UserPlatforms::find()->where(['user_id'=>$bp_child['id'],'platform_id'=>6])->one();
                                if(!empty($child_mat2)){
                                    $if2 = true;
                                }
                            }
                            if($if1 and $if2){
                                self::plusToMatrix($big_parent->user_id,6);
                            }
                        }else{
                            self::plusToMatrix($big_parent->user_id,$big_parent->platform_id+1);
                        }

                    }

                }

            }

        }
        $matrix->deleted = 2;
        $matrix->save();
    }
}
