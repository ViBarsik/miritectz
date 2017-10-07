<?php
namespace app\models\credit;

use Yii;

class CreditApi
{
    private $Credit;

    public function __construct(Credit $Credit) {
        $this->Credit = $Credit;
    }

    public function createPaymentCollection(){
        $startAmount = $this->Credit->amount;        
        $percent = $this->Credit->percent / 100 / 12;
        $startDate = new \DateTime($this->Credit->start_date);
        $monthlyPayment = $this->calculateMonthlyPayment($startAmount, $percent, $this->Credit->number_of_months);

        for($i = 1; $i <= $this->Credit->number_of_months; $i++){
            $percentPayment = $startAmount * $percent;
            $payment = $monthlyPayment - $percentPayment;

            $Payment = $this->paymentWrite([
                'credit_id' => $this->Credit->credit_id,
                'payment_date' => $startDate->format('d-m-Y'),
                'payment' => $payment,
                'percent_payment' => $percentPayment,
                'total_payment' => $monthlyPayment,
                'loan_balance' => $startAmount
            ]);

            if($Payment){
                $this->Credit->addPaymentToCollection($Payment);
            }
            
            $startAmount -= $payment;           
            $startDate->add(new \DateInterval('P1M'));
        }        
        return $this;
    }
    
    public function getCredit() : Credit {
        return $this->Credit;
    }

    private function calculateMonthlyPayment(float $amount, float $percent, int $number_of_months){
        return $amount * ($percent + ($percent / (pow(1 + $percent, $number_of_months) - 1)));
    }

    private function paymentWrite($attributes){
        $Payment = CreditPayment::createObject('create',$attributes);
        
        if(!$Payment->validate()){
            return null;
        }

        $Payment->create();
        return $Payment;
    }
}