<?php
/**
 * layout for update|create Books (actionUpdate()/actionCreate)
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preview')->textInput() ?>

    <?= $form->field($model, 'author')->textInput(['value'=> ( (isset($model->author->firstname)) ? $model->author->firstname." ".$model->author->lastname : Yii::t('app','UNKNOWN author')),
                                                  'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'date')
        ->widget( DatePicker::className(), [
            'addon' => '',
            'language' => 'ru',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]])
        ->textInput(['value' => ($model->date) ? Yii::$app->formatter->asDate($model->date, 'yyyy-MM-dd') : ''
                     , 'placeholder' => 'yyyy-MM-dd']) ?>

    <?php // доступны поля только во время редакции
        // если потребуется редактировать поля - убрать 'disabled' => 'disabled', и в \BooksController::actionUpdate поправить date_update
        if(!$model->isNewRecord) {
        echo $form->field($model, 'date_create')
        ->widget( DatePicker::className(), [
            'addon' => '',
            'language' => 'ru',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]])
        ->textInput(['value' => ($model->date_create) ? Yii::$app->formatter->asDate($model->date_create, 'yyyy-MM-dd') : ''
                     , 'placeholder' => 'yyyy-MM-dd'
                     , 'disabled' => 'disabled']);

        echo $form->field($model, 'date_update')
            ->widget(DatePicker::className(), [
                'addon' => '',
                'language' => 'ru',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ])
            ->textInput(['value' => ($model->date_update) ? Yii::$app->formatter->asDate($model->date_update, 'yyyy-MM-dd') : ''
                         , 'placeholder' => 'yyyy-MM-dd'
                         , 'disabled' => 'disabled']);
    } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
