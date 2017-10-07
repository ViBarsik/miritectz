<?php
namespace app\models\traits;

use app\models\Model;

trait TDateValidator
{    
    public function validateDate($attribute, $params)
    {
        $timestampNow = time();
        $timestamp = (int)strtotime($this->$attribute);
        if ($timestamp < $timestampNow || $timestamp > $timestampNow + 360 * 24 * 365 ) {
            $this->addError($attribute, 'Неверный формат даты.');
        }
    }
}