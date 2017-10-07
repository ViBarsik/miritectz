<?php
namespace app\models\credit;

use Yii;

class Credit extends \app\models\Model
{
    use \app\models\traits\TDateValidator;
    
    public $credit_id; //PrimaryKey
    public $client_id;
    public $amount = 1000000;
    public $number_of_months = 18;
    public $percent = 25;
    public $start_date = '08-10-2017';
    public $status = 1;
    public $create_time;
    
    private $Payments = [];

    public static function tableName() {
        return 'credit';
    }   

    public function scenarios()
    {
        return [
            'default' => ['credit_id','client_id','amount','number_of_months','percent','start_date','status','create_time'],
            'create' => ['client_id','amount','number_of_months','percent','start_date','status','create_time'],
        ];
    }

    public function rules()
    {
        return [
            ['amount', 'double', 'min' => 1, 'max' => 1000000000],
            ['number_of_months', 'integer', 'min' => 1, 'max' => 300],
            ['status', 'integer', 'min' => 0, 'max' => 1],
            ['percent', 'double', 'min' => 0.01],
            ['start_date', 'validateDate'],

            [['amount','number_of_months','percent','start_date'], 'required', 'on'=>'create' ],
        ];
    }

    public function create(){
        $this->client_id = rand(1,1000000000);
        $this->create_time = time();        
        
        Yii::$app->db->createCommand()->insert(self::tableName(), $this->getAttributes())->execute();
        $this->credit_id = Yii::$app->db->lastInsertID;
        
        return $this;
    }    
    
    public function addPaymentToCollection(CreditPayment $payment){
        $this->Payments[] = $payment;
    }

    public function getPayments() : array {
        return $this->Payments;
    }   
    
}