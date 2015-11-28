<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Books */



$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="books-view">

    <h1><?= Html::encode($this->title); ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'preview',
                'format' => 'raw',
                'value'=> Html::a(Html::img("/backend/web/img/small/".$model->preview, [
                    'alt' => $model->name,
                    'style' => 'height:100px;'
                ]),
                                  "/backend/web/img/original/".$model->preview,
                                  [ 'title' => $model->name, 'rel' => 'fancybox', 'target'=>'_blank' ] ),
                'label' => Yii::t('app', 'Photo'),
            ],
            [
                'attribute'=> 'author',
                'value'=> ( (isset($model->author->firstname)) ? $model->author->firstname." ".$model->author->lastname : Yii::t('app','UNKNOWN author')),
                //'attribute'=> 'author_fullname',
                //'value'=> $model->author->author_fullname,
            ],
            [
                'attribute' => 'date',
                'value' => Yii::$app->formatter->asDate($model->date, 'php:d F Y'),
            ],
            [
                'attribute' => 'date_create',
                'value' => $model->getBooksRelativeDate($model->date_create),
                //'value' => Yii::$app->formatter->asDate($model->date_create, 'php:d F Y'),
            ],
            [
                'attribute' => 'date_update',
                'value' => $model->getBooksRelativeDate($model->date_update),
                //'value' => Yii::$app->formatter->asDate($model->date_update, 'php:d F Y'),
            ],
        ],
    ]) ?>

</div>
