<?php
namespace app\models\logic;

use Yii;

/**
 * Class File
 * @package app\models\logic
 * @property FileReader $reader
 * @property FileWriter $writer
 */
class File
{
    const REPLACE_FROM_WORDS_POSITION = 'FromPosition';
    const REPLACE_FROM_COUNT_CHARS = 'FromCountChar';
    
    const REVERT_WORDS = [
        '01' => '-ТРИ-',
        '10' => '-ПЯТЬ-',
        '11' => '-ПЯТНАДЦАТЬ-',

    ];    

    private $replacedText  = ''; 
    

    public function readInputFile( string $file ){
        $this->reader = self::getReader($file);
        
        return $this;
    }

    public function replaceText(string $replaceType){
        $this->replacedText =  $this->{'replaceWords' . $replaceType}();
        
        return $this;
    }

    public function writeOutputFile( string $fileName){        
        self::getWriter($fileName)->write($this->replacedText);
        
        return true;
    }

    

    private function replaceWordsFromCountChar() : string {
        $replacedWords = [];

        foreach($this->reader->getWords() as $word){
            $wordLength = mb_strlen($word);
            $keyRewertWord = (int)($wordLength % 5 === 0) . (int)($wordLength % 3 === 0);
            if($keyRewertWord === '00'){
                continue;
            }
            $replacedWords[$word] = self::REVERT_WORDS[$keyRewertWord];
        }
        
        return strtr($this->reader->getContent(),$replacedWords);
    }

    private function replaceWordsFromPosition() : string {
        $words = explode(' ', $this->reader->getContent());

        $iterator = 1;
        foreach($words as &$word){
            if(preg_match('/[[:alpha:]]+/ui', $word)) {
                if(strpos($word, '-', 1)){                    
                    $subCollectionWords = explode('-', $word);

                    foreach($subCollectionWords as &$subWord){                        
                        if(preg_match('/[[:alpha:]]+/ui', $subWord)) {
                            $subWord = $this->replaceWord($iterator, $subWord);
                            $iterator++;
                        }
                    }

                    $word = implode('-', $subCollectionWords);
                } else {
                    $word = $this->replaceWord($iterator, $word);
                    $iterator++;
                }
            }
        }
        
        return implode(' ', $words);
    }
    
    private function replaceWord(int $iterator, string $word) : string{
        $keyRevertWord = (int)($iterator % 5 === 0) . (int)($iterator % 3 === 0);
        if($keyRevertWord !== '00'){
            return preg_replace('/[[:alpha:]]+/ui', self::REVERT_WORDS[$keyRevertWord], $word);
        }
        return $word;
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
    
    
 