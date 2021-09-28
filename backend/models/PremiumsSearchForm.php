<?php


namespace backend\models;

use yii\base\Model;
class PremiumsSearchForm extends Model
{

    public $username;
    public $date_start;
    public $date_end;
    public $time_start;
    public $time_end;

    function rules()
    {
        return [
            ['username', 'string'],
            [['date_start','date_end'], 'string'],
        ];
    }

    public function setTimes(){
        if (isset($this->date_start)){
            $this->time_start = strtotime($this->date_start);
        }
        if (isset($this->date_end)) {
            $this->time_end = strtotime($this->date_end);
        }

    }



}