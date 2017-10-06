<?php
namespace app\models\logic;

use Yii;

class File
{
    const REVERT_WORDS = [
        '01' => '-ТРИ-',
        '10' => '-ПЯТЬ-',
        '11' => '-ПЯТНАДЦАТЬ-',

    ];   

    public function readInputFile( string $file ){
        $this->reader = self::getReader($file);
        
        return $this;
    }

    public function writeOutputFile( string $file ){
        self::getWriter($file)->write($this->replaceText());
        
        return $this;
    }

    private function replaceText(){
        $text = $this->reader->getContent();
        $replacedWords = $this->replaceWords();

        return strtr($text,$replacedWords);
    }

    private function replaceWords(){
        $replacedWords = [];

        foreach($this->reader->getWords() as $key=>$word){
            $wordLength = mb_strlen($word);
            $keyRewertWord = (int)($wordLength % 5 === 0) . (int)($wordLength % 3 === 0);
            if($keyRewertWord === '00'){
                continue;
            }
            $replacedWords[$word] = self::REVERT_WORDS[$keyRewertWord];
        }
        
        return $replacedWords;
    }    
    
    public static function getReader( string $file ) : FileReader{
        if(!file_exists( $file )){
            self::createFile( $file );
        }

        return new FileReader($file);
    }

    public static function getWriter( string $file ) : FileWriter{
        if(!file_exists( $file )){
            self::createFile( $file );
        }
        
        return new FileWriter($file);
    }
    
    public static function createFile( string $file){
        $newFile = fopen($file, "w");
        fwrite($newFile, '');
        fclose($newFile);
    }
}
    
    
 