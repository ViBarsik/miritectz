<?php
/* @var $this yii\web\View */
/* @var $Credit app\models\credit\Credit */
/* @var $Payment app\models\credit\CreditPayment */

?>

<table class="table">
    <tr>
        <th>№ платежа</th>
        <th>Дата платежа</th>
        <th>Основной долг</th>
        <th>Проценты (<?=$Credit->percent?>%/год)</th>
        <th>Общая сумма</th>
        <th>Остаток долга</th>
    </tr>

    <?php foreach($Credit->getPayments() as $Payment) : ?>
        <tr>
            <td><?=$Payment->payment_id?></td>
            <td><?=$Payment->payment_date?></td>
            <td><?=number_format((float)$Payment->payment, 2, ".", " ")?></td>
            <td><?=number_format((float)$Payment->percent_payment, 2, ".", " ")?></td>
            <td><?=number_format((float)$Payment->total_payment, 2, ".", " ")?></td>
            <td><?=number_format((float)$Payment->loan_balance, 2, ".", " ")?></td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <th colspan="2">Сумма кредита: <?=number_format((float)$Credit->amount, 2, ".", " ")?></th>
        <th colspan="2">Переплата по процентам: <?=number_format((float)($Credit->number_of_months * $Credit->getPayments()[0]->total_payment - $Credit->amount), 2, ".", " ")?></th>
        <th>Срок: <?=$Credit->number_of_months?> мес</th>
        <th>Процент: <?=$Credit->percent?>%/год</th>
    </tr>
</table>

<? if(Yii::$app->request->isAjax) : ?>
    <script type="text/javascript">
        MessageBox("success", "История сохранена в базе данных");
    </script>
<? endif; ?>
