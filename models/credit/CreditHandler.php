<?php

namespace app\models\credit;

use Yii;
use app\models\ModelHandler;

class CreditHandler extends ModelHandler
{    
    public function createNewCredit() : Credit{
        $Credit = $this->getCreditObject('create', Yii::$app->request->post('Credit'));

        if($Credit->validate()){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $Credit->create();
                $Credit = (new CreditApi($Credit))->createPaymentCollection() -> getCredit();
                $transaction->commit();
            } catch (\Exception $e){
                $transaction->rollBack();
                exit($e->getMessage());
            }            
        }        
        return $Credit;
    }

    public function getCreditPayments(Credit $Credit){        
        return $this->getCreditPaymentObject(['credit_id'=>$Credit->credit_id])->findPayments();
    }

    public function getCreditObject(string $scenario = 'default', array $attributes = []) : Credit{
        return Credit::createObject($scenario,$attributes);
    }

    public function getCreditPaymentObject(string $scenario = 'default', array $attributes = []) : CreditPayment{        
        return CreditPayment::createObject($scenario,$attributes);
    }

}