<?php
/* @var $this yii\web\View */
/* @var $Credit app\models\credit\Credit */

use yii\helpers\Html;

$this->title = 'Предметная область';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/css/views/site/credit.css');
$this->registerCssFile('//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');

$this->registerJsFile('//code.jquery.com/ui/1.12.1/jquery-ui.js', ['depends' => [ 'yii\web\JqueryAsset' ] ]);
$this->registerJsFile('/js/views/site/credit.js', ['depends' => [ 'yii\web\JqueryAsset' ] ]);
?>

<h1><?= Html::encode("Расчет кредитных выплат") ?></h1>
<hr>

<h2>Основная задача</h2>
<div>
    Написать скрипт кредитного калькулятора используя PHP и Javascript (можно любые сторонние JS библиотеки, оформление - Bootstrap).
    В задаче необходимо реализовать:
    <ul>
        <li>Клиент вводит сумму кредита, указывает срок кредита в месяцах и процентную ставку, дату первого платежа. Скрипт выводит
            таблицу с графиком платежей в формате: № платежа, Дата платежа, Основной долг, проценты, общая сумма, остаток долга.</li>
        <li>Вид платежа - аннуитетный.</li>
        <li>Расчет происходит на стороне Backend все данные отправляются AJAX запросами.</li>
        <li>История подсчетов и график платежей сохраняются в MySQL для последующего анализа.</li>
    </ul>        
</div>

<h2>Решение</h2>

<div id="creditInfo">
    <?=$this->render('form',['Credit' => $Credit])?>
</div>