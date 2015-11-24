<?php

use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Yii books';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Привет!</h1>
        <h2>Если ты не зарегистрирован - регистрируйся и переходи в админку с книгами!</h2>

        <?= Html::a("Зарегистрироваться", ['/site/signup'], ['class' => 'btn btn-success']) ?>

        <?= Html::a("Перейти в админку и Книги", '/backend/web/books/index', ['class' => 'btn btn-success']) ?>
    </div>

    <h3>*** Если сайт открыт в первый раз - не забудь сделать миграцию таблиц: пользователей, книг, авторов (php yii migrate)</h3>
</div>
