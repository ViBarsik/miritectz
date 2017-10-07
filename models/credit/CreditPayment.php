<?php
/**
 * Created by PhpStorm.
 * User: CTO
 * Date: 06.10.2017
 * Time: 20:16
 */

namespace app\models\credit;

use Yii;

class CreditPayment extends \app\models\Model
{
    use \app\models\traits\TDateValidator;
    
    public $payment_id; 
    public $credit_id;  
    public $payment_date;
    public $payment;
    public $percent_payment;
    public $total_payment;
    public $loan_balance;

    public static function tableName() {
        return 'credit_payment';
    }

    public function scenarios()
    {
        return [
            'default' => ['payment_id','credit_id','payment_date','payment','percent_payment','total_payment','loan_balance'],
            'create' => ['credit_id','payment_date','payment','percent_payment','total_payment','loan_balance'],
        ];
    }

    public function rules()
    {
        return [
            [['payment','percent_payment','total_payment','loan_balance'], 'double', 'min' => 1, 'max' => 1000000000],
            ['credit_id', 'integer', 'min' => 1],
            ['percent', 'double', 'min' => 0.01],
            ['start_date', 'validateDate'],
            [['credit_id','payment_date','payment','percent_payment','total_payment','total_payment','loan_balance'], 'required', 'on'=>'create' ],
        ];
    }

    public function create(){        
        Yii::$app->db->createCommand()->insert(self::tableName(), $this->getAttributes())->execute();
        $this->payment_id = Yii::$app->db->lastInsertID;
        
        return $this;
    }    
}