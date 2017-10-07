<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $Credit app\models\credit\Credit */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'id' => 'credit-create-form',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-offset-2 col-lg-10\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>

    <?= $form->field($Credit, 'amount')->textInput(['autofocus' => true])->hint('Введите сумму кредита')->label('Сумма кредита'); ?>
    <?= $form->field($Credit, 'number_of_months')->textInput()->hint('Введите колличество месяцев')->label('Срок (в месяцах)'); ?>
    <?= $form->field($Credit, 'percent')->textInput()->hint('Введите годовой процент')->label('Процентная ставка'); ?>
    <?= $form->field($Credit, 'start_date')->textInput()->hint('Введите дату начала')->label('Дата первого платежа'); ?>
    <div class="form-group text-right">
        <div class="col-lg-12">
            <?= Html::submitButton('Заказать кредит', ['class' => 'btn btn-primary', 'name' => 'create-credit-button']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>

<? if(Yii::$app->request->isAjax) : ?>
    <script type="text/javascript">
        $( "#credit-start_date" ).datepicker({
            dateFormat : 'dd-mm-yy',
            minDate : 0,
            maxDate: 365
        });
    </script>
<? endif; ?>
