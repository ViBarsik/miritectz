<?php
namespace app\models\logic;

use yii\base\ErrorException;
use yii\base\Object;

class FileWriter extends Object
{
    private $filename;    

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function write(string $content){
        $file = fopen($this->filename, "w");
        fwrite($file, $content);        
        fclose($file );
    }
    
}