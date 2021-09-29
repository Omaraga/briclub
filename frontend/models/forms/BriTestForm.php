<?php

namespace frontend\models\forms;
use yii\base\Model;
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

    public  function generateUsers(){

    }

    public static function destroyUsers(){

    }

}