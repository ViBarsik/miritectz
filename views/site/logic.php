<?php
/* @var $this yii\web\View */
/* @var $inputFile string */
/* @var $outputWordsPositionFile string */
/* @var $outputCountCharFile string */

use yii\helpers\Html;

$this->title = 'Обработка текстового файла';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<h2>Основная задача</h2>
<div>
    Дается случайный текст в файле (файл следует приложить к заданию, содержание не имеет значения, кодировка - UTF-8). 
    PHP скрипт должен прочитать текст и заменить каждое слово в тексте, позиция которого делится без остатка на 3 - словом -ТРИ-, 
    каждое слово, позиция которого делится без остатка на 5 - словом -ПЯТЬ-, а если позиция слова делится без остатка и на 3 и на 5 - 
    заменить его словом -ПЯТНАДЦАТЬ-. После обработки текста - результат сохранить в новом файле
</div>

<h2>Решение</h2>
<h3>Входной файл (/web/logic/input.txt)</h3>
<div><?=$inputFile?></div>

<h3>Выходной файл c заменой по позициям слов (/web/logic/output_count_char.txt)</h3>
<div><?=$outputWordsPositionFile?></div>

<h3>Выходной файл c заменой по колличеству букв в слове (/web/logic/output_words_position.txt)</h3>
<div><?=$outputCountCharFile?></div>


