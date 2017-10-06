<?php

namespace app\models\logic;

use Yii;
use app\models\ModelHandler;

class LogicHandler extends ModelHandler
{
    public function getViewData() : array {
        $data = [];
        $inputFile = Yii::getAlias('@webroot/logic/input.txt');
        $outputFile = Yii::getAlias('@webroot/logic/output.txt');

        (new File())->readInputFile($inputFile)->writeOutputFile($outputFile);
        
        $data['inputFile'] = File::getReader($inputFile)->getContent();
        $data['outputFile'] = File::getReader($outputFile)->getContent();
        
        return $data;
    }    
}