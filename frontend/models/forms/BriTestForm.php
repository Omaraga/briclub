<?php

namespace frontend\models\forms;
use common\models\DohodCompany;
use common\models\User;
use yii\base\Model;
use common\models\Referals;
use common\models\MatrixRef;
use Yii;

class BriTestForm extends Model
{
    public $complete;
    public $branch;

    public function rules()
    {
        return [
            [['branch', 'complete'], 'safe'],
        ];
    }

    private static function createUser($parent , $j){

        $user = new User();
        $user->username = 'test'.$j;
        $user->email = 'test'.$j.'@test.kz';
        $user->fio = 'Test Testov';
        $user->firstname = 'Test';
        $user->lastname = 'Testov';
        $user->phone = '8777777777';
        $user->country_id = 1;
        $user->w_balans = 5000;

        if(!empty($parent)){
            $user->parent_id = $parent->id;
        }
        $user->setPassword('qwerty');
        $user->generateAuthKey();
        if($user->save()){
            Referals::setParents($user['id']);
            $user->w_balans = $user->w_balans - 5000;
            $user->save();
            MatrixRef::plusToRefMatrix($user['id'], 1, true, 0, true, null, 1);
            return $user;
        }else{
            return null;
        }

    }

    public function generateUsers(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $localBranch = intval($this->complete / $this->branch);
        $x = $localBranch;
        $log_y = log($x +1, 2);
        $y = pow(2, $log_y+6) -1;
        $j = 0;
        for ($i = 0; $i < $this->branch; $i++){
            $user = self::createUser(null, $j);
            $j++;
            $list = [$user];
            $count = 1;
            while (sizeof($list) > 0){
                $node = array_shift($list);

                if($count < $y){
                    $left_user = self::createUser($node, $j);
                    $j++;
                    $count++;
                    array_push($list, $left_user);
                    $right_user = self::createUser($node, $j);
                    $j++;
                    $count++;
                    array_push($list, $right_user);
                }

            }

        }
    }

    public static function destroyData(){
        Yii::$app->db->createCommand()->truncateTable('user')->execute();
        Yii::$app->db->createCommand()->truncateTable('matrix_ref')->execute();
        Yii::$app->db->createCommand()->truncateTable('mat_clons')->execute();
        Yii::$app->db->createCommand()->truncateTable('matblocks')->execute();
        Yii::$app->db->createCommand()->truncateTable('mat_parents')->execute();
        Yii::$app->db->createCommand()->truncateTable('referals')->execute();
        Yii::$app->db->createCommand()->truncateTable('dohod_company')->execute();
        Yii::$app->db->createCommand()->truncateTable('actions')->execute();
        Yii::$app->db->createCommand()->truncateTable('actions')->execute();
        Yii::$app->db->createCommand()->truncateTable('bri_tokens')->execute();
        Yii::$app->db->createCommand()->truncateTable('tokens')->execute();

    }

}