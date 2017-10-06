<?php

/* @var $this yii\web\View */

$this->title = 'Logical Quiz';

$this->registerCssFile('/css/views/site/index.css');
$this->registerJsFile('/js/views/site/index.js', ['depends' => [ 'yii\web\JqueryAsset' ] ]);
?>

<canvas id="flying-bubbles"></canvas>

<div class="site-index">
    <div class="jumbotron">
        <h1>Добро пожаловать в тестовое задание от Miritec!</h1>
        <p>
            <a class="btn btn-lg btn-success" href="/logica">Логика</a>
            <a class="btn btn-lg btn-success" href="/debug">Дебаггинг</a>
            <a class="btn btn-lg btn-success" href="/task">Задача</a>
        </p>
    </div>

</div>


