<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Books */


// редирект на авторизацию - при прямой переходе на страницу
if (\Yii::$app->getUser()->isGuest && \Yii::$app->getRequest()->url !== Url::to(\Yii::$app->getUser()->loginUrl)) {
    \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
}


$this->title = Yii::t('app', 'Create Books');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
