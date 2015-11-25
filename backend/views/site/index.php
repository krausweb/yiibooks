<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Yii Books';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Ура, ты в админке!</h1>
        <?= Html::a(Yii::t('app', 'Go to section Books'), ['/books/index'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
