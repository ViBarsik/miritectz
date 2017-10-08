<?php

namespace app\models\logic;

use Yii;
use app\models\ModelHandler;

class LogicHandler extends ModelHandler
{
    public function getViewData() : array {
        $data = [];
        $inputFile = Yii::getAlias('@webroot/logic/input.txt');
        $outputCountCharFile = Yii::getAlias('@webroot/logic/output_count_char.txt');
        $outputWordsPositionFile = Yii::getAlias('@webroot/logic/output_words_position.txt');

        $FileObject = new File();
        $FileObject->readInputFile($inputFile)->replaceText(File::REPLACE_FROM_WORDS_POSITION)->writeOutputFile($outputWordsPositionFile);
        $FileObject->readInputFile($inputFile)->replaceText(File::REPLACE_FROM_COUNT_CHARS)->writeOutputFile($outputCountCharFile);

        $data['inputFile'] = File::getReader($inputFile)->getContent();
        $data['outputWordsPositionFile'] = File::getReader($outputWordsPositionFile)->getContent();
        $data['outputCountCharFile'] = File::getReader($outputCountCharFile)->getContent();

        return $data;
    }    
}