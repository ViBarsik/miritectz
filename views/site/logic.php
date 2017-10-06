<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $User app\models\user\User */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Обработка текстового файла';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<h2>Входной файл</h2>
<div><?=$inputFile?></div>

<h2>Выходной файл</h2>
<div><?=$outputFile?></div>


