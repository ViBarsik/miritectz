<?php

namespace app\models\logic;

use yii\base\ErrorException;
use yii\base\Object;

class FileReader extends Object
{
    private $content;    

    public function __construct(string $filename)
    {
        $this->content = file_get_contents($filename);
    }
    
    public function getContent(){
        return $this->content;
    }

    public function getWords() : array{
        preg_match_all('/[[:alpha:]]+/ui', $this->content, $words);         
        return $words[0];
    }
}