<?php

namespace frontend\modules\advcash\widgets;

use yii\base\Widget;
use frontend\modules\advcash\models\AdvcashSci;
use frontend\modules\advcash\models\AdvcashWithdraw;

/**
 * Виджет для вывода формы ввода/вывода средств в публичке
 * По умолчанию тип стоит для вывода
 */
class AdvcashWidget extends Widget
{
    public $model;
    public $result;
    public $type = 'sci';
    public $errors;

    public function run()
    {
        if ($this->type == 'sci') {
            if ($this->model == null) $this->model = new AdvcashSci();
            if ($this->result == null) $this->result = false;
            if ($this->result) $view = 'redirect';
            else $view = 'createDeposit';
        }
        elseif ($this->type == 'api') {
            if ($this->model == null) $this->model = new AdvcashWithdraw();
            $view = 'createWithdraw';
        }
        else return null;
        
        return $this->render($view, ['model' => $this->model, 'result' => $this->result, 'errors' => $this->errors]);
    }
}