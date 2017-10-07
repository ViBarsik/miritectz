<?php
namespace app\models\install;

use Yii;

class Generator
{
    public static function createTables() {
        return Yii::$app->db->createCommand("
            CREATE TABLE IF NOT EXISTS `credit` (
                `credit_id` INT(11) NOT NULL AUTO_INCREMENT,
                `client_id` INT(11) NOT NULL,
                `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
                `number_of_months` SMALLINT(7) NOT NULL,
                `percent` TINYINT(3) NOT NULL,
                `start_date` VARCHAR(10) NOT NULL,
                `status` TINYINT(3) NOT NULL DEFAULT '1',
                `create_time` INT(11) NOT NULL,
                PRIMARY KEY (`credit_id`),
                INDEX `client_id`(`client_id`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;

            CREATE TABLE IF NOT EXISTS `credit_payment` (
                `payment_id` INT(11) NOT NULL AUTO_INCREMENT,
                `credit_id` INT(11) NOT NULL,
                `payment_date` VARCHAR(10) NOT NULL,
                `payment` DECIMAL(12,2) NOT NULL,
                `percent_payment` DECIMAL(12,2) NOT NULL,
                `total_payment` DECIMAL(12,2) NOT NULL,
                `loan_balance` DECIMAL(12,2) NOT NULL,
                PRIMARY KEY (`payment_id`),
                INDEX `credit_id` (`credit_id`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;            
        ")->execute();
    }

    public static function updateConfig () {
        $params = Yii::$app->params;
        $params['isInstall'] = true;

        $fileObj = fopen(Yii::$aliases['@app'].'/config/params.php', "w");
        fwrite($fileObj, '<?php ' . "\r\n");
        fwrite($fileObj, 'return ' . var_export($params,true) . ';');
        fclose($fileObj );

        return true;
    }
}