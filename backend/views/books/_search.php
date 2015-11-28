<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\BooksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="books-search">

    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div>
        <?php $authors_all = $model->getAuthors();
            $authors_all[] = Yii::t('app','UNKNOWN author');
            echo $form->field($model, 'author_fullname')->label(false)
                ->dropDownList( $authors_all, ['prompt'=> Yii::t('app','author select'), 'class'=>'form-control search_author'] ); ?>

        <?= $form->field($model, 'name')->label(false)->textInput(['placeholder' => 'название книги', 'class'=>'form-control search_author']) ?>
    </div>

    <div class="form-group search_box_right">
        <?= Html::a(Yii::t('app', 'Create Books'), ['create'], ['class' => 'btn btn-success',
                        'data-pjax'=>'0', 'data-toggle'=>'modal', 'data-target'=>'#create_modal'
        ]) ?>
        <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog"><div class="modal-content"></div></div>
        </div>
    </div>

    <div style="clear:both;">
        <?= $form->field($model, 'book_date_from')->label('Дата выхода книги:',['class'=>'search_author_date_label'])
            ->widget( DatePicker::className(), [
                'addon' => '',
                'language' => 'ru',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy',
                ]])
            ->textInput(['class'=>'form-control search_author_date_input', 'placeholder'=>'31/12/2014']) ?>

        <?= $form->field($model, 'book_date_to')->label('до',['class'=>'search_author_date_label_two'])
            ->widget( DatePicker::className(), [
                'addon' => '',
                'language' => 'ru',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd/mm/yyyy',
                ]])
            ->textInput(['class'=>'form-control search_author_date_input', 'placeholder'=>'31/02/2015']) ?>
    </div>

    <div class="form-group search_box_right">
        <?= Html::submitButton(Yii::t('app', 'Искать'), ['class' => 'btn btn-primary search_btn']) ?>
    </div>
    <div style="clear:both;"></div>

    <?php ActiveForm::end(); ?>

</div>
