<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "referals".
 *
 * @property int $id
 * @property int $user_id
 * @property int $parent_id
 * @property int $level
 * @property int $shoulder
 * @property int $time
 * @property int $activ
 * @property int $time_start
 * @property int $time_personal
 * @property int $time_global
 * @property int $maximum
 */
class Referals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'referals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_id', 'level', 'shoulder', 'time', 'activ', 'time_start', 'time_personal', 'time_global', 'maximum'], 'integer'],
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
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'shoulder' => 'Shoulder',
            'time' => 'Time',
            'activ' => 'Activ',
            'time_start' => 'Time Start',
            'time_personal' => 'Time Personal',
            'time_global' => 'Time Global',
            'maximum' => 'Maximum',
        ];
    }
    public static function setParents($user_id,$matrix=null){
        $user = User::findOne($user_id);
        if(!empty($user['parent_id'])){
            $referal_old = Referals::find()->where(['user_id'=>$user['id'],'level'=>1])->one();
            if(!empty($referal_old)){

                $referal = Referals::findOne($referal_old['id']);
                if($matrix == 'start'){
                    $referal->time_start = time();
                    $referal->activ = 1;
                    if(empty($referal->shoulder)){
                        $parent_children = Referals::find()->where(['parent_id'=>$user['parent_id'],'level'=>1,'activ'=>1])->count();
                        if($parent_children % 2 == 0){
                            $referal->shoulder = 1;
                        }else{
                            $referal->shoulder = 2;
                        }
                    }

                    $referal->save();
                    $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                    foreach ($parent_referals as $parent_referal) {
                        $referal = Referals::find()->where(['parent_id'=>$parent_referal['parent_id'],'user_id'=>$referal['user_id'],'level'=>$parent_referal['level']+1])->one();
                        if(!empty($referal)){
                            $referal->activ = 1;
                            $referal->shoulder = $parent_referal['shoulder'];
                            $referal->time_start = time();
                            $referal->save();
                        }
                    }

                }elseif($matrix == 'personal'){
                    $referal->time_personal = time();
                    $referal->activ = 1;
                    if(empty($referal->shoulder)){
                        $parent_children = Referals::find()->where(['parent_id'=>$user['parent_id'],'level'=>1,'activ'=>1])->count();
                        if($parent_children % 2 == 0){
                            $referal->shoulder = 1;
                        }else{
                            $referal->shoulder = 2;
                        }
                    }

                    $referal->save();
                    $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                    foreach ($parent_referals as $parent_referal) {
                        $referal = Referals::find()->where(['parent_id'=>$parent_referal['parent_id'],'user_id'=>$referal['user_id'],'level'=>$parent_referal['level']+1])->one();
                        if(!empty($referal)){
                            $referal->activ = 1;
                            $referal->shoulder = $parent_referal['shoulder'];
                            $referal->time_personal = time();
                            $referal->save();
                        }
                    }
                }elseif($matrix == 'global'){
                    $referal->time_global = time();
                    $referal->activ = 1;
                    if(empty($referal->shoulder)){
                        $parent_children = Referals::find()->where(['parent_id'=>$user['parent_id'],'level'=>1,'activ'=>1])->count();
                        if($parent_children % 2 == 0){
                            $referal->shoulder = 1;
                        }else{
                            $referal->shoulder = 2;
                        }
                    }

                    $referal->save();
                    $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                    foreach ($parent_referals as $parent_referal) {
                        $referal = Referals::find()->where(['parent_id'=>$parent_referal['parent_id'],'user_id'=>$referal['user_id'],'level'=>$parent_referal['level']+1])->one();
                        if(!empty($referal)){
                            $referal->activ = 1;
                            $referal->shoulder = $parent_referal['shoulder'];
                            $referal->time_global = time();
                            $referal->save();
                        }
                    }
                }else{
                    $referal->save();
                }

            }else{
                $referal = new Referals();
                $referal->user_id = $user['id'];
                $referal->parent_id = $user['parent_id'];
                $referal->level = 1;

                if($matrix == 'start'){
                    $referal->activ = 1;
                    $referal->time_start = time();
                    $referal->save();
                    $user->time_start = time();
                    $user->save();
                    $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                    foreach ($parent_referals as $parent_referal) {
                        $referal = new Referals();
                        $referal->activ = 1;
                        $referal->user_id = $user['id'];
                        $referal->parent_id = $parent_referal['parent_id'];
                        $referal->level = $parent_referal['level'] +1;
                        $referal->time_start = time();
                        $parent_children = Referals::find()->where(['parent_id'=>$user['parent_id'],'level'=>1,'activ'=>1])->count();
                        if($parent_children % 2 == 0){
                            $referal->shoulder = 1;
                        }else{
                            $referal->shoulder = 2;
                        }
                        $referal->save();
                    }
                }elseif($matrix == 'personal'){
                    $user->time_personal = time();
                    $user->save();
                    $referal->activ = 1;
                    $referal->time_personal = time();
                    $parent_children = Referals::find()->where(['parent_id'=>$user['parent_id'],'level'=>1,'activ'=>1])->count();
                    if($parent_children % 2 == 0){
                        $referal->shoulder = 1;
                    }else{
                        $referal->shoulder = 2;
                    }
                    $referal->save();
                    $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                    foreach ($parent_referals as $parent_referal) {
                        $referal = new Referals();
                        $referal->activ = 1;
                        $referal->user_id = $user['id'];
                        $referal->parent_id = $parent_referal['parent_id'];
                        $referal->level = $parent_referal['level'] +1;
                        $referal->shoulder = $parent_referal['shoulder'];
                        $referal->time_personal = time();
                        $referal->save();
                    }
                }elseif($matrix == 'global'){
                    $user->time_global = time();
                    $user->save();
                    $referal->time_global = time();
                    $referal->activ = 1;
                    $parent_children = Referals::find()->where(['parent_id'=>$user['parent_id'],'level'=>1,'activ'=>1])->count();
                    if($parent_children % 2 == 0){
                        $referal->shoulder = 1;
                    }else{
                        $referal->shoulder = 2;
                    }
                    $referal->save();
                    $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                    foreach ($parent_referals as $parent_referal) {
                        $referal = new Referals();
                        $referal->activ = 1;
                        $referal->user_id = $user['id'];
                        $referal->parent_id = $parent_referal['parent_id'];
                        $referal->level = $parent_referal['level'] +1;
                        $referal->shoulder = $parent_referal['shoulder'];
                        $referal->time_global = time();
                        $referal->save();
                    }
                }else{
                    $referal->time = time();
                    $referal->save();
                    $parent_referals = Referals::find()->where(['user_id'=>$user['parent_id']])->all();
                    foreach ($parent_referals as $parent_referal) {
                        $referal = new Referals();
                        $referal->user_id = $user['id'];
                        $referal->parent_id = $parent_referal['parent_id'];
                        $referal->level = $parent_referal['level'] +1;
                        $referal->time = time();
                        $referal->save();
                    }
                }


            }
        }
    }
    public static function getParent($user_id){
        $user = User::findOne($user_id);
        if(!empty($user['parent_id'])){
            return User::findOne($user['parent_id']);
        }else{
            return false;
        }
    }
}
