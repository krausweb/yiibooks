<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use newerton\fancybox\FancyBox;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BooksSearch */
/* @var $authorsModel app\models\Authors */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('app', 'Books');
$this->params['breadcrumbs'][] = Yii::t('app', 'Books');

echo FancyBox::widget([
            'target' => 'a[rel=fancybox]',
            'helpers' => true,
            'mouse' => true,
            'config' => [
                'maxWidth' => '90%',
                'maxHeight' => '90%',
                'playSpeed' => 7000,
                'padding' => 0,
                'fitToView' => false,
                'width' => '70%',
                'height' => '70%',
                'autoSize' => false,
                'closeClick' => false,
                'openEffect' => 'elastic',
                'closeEffect' => 'elastic',
                'prevEffect' => 'elastic',
                'nextEffect' => 'elastic',
                'closeBtn' => false,
                'openOpacity' => true,
                'helpers' => [
                    'title' => ['type' => 'float'],
                    'buttons' => [],
                    'thumbs' => ['width' => 68, 'height' => 50],
                    'overlay' => [
                        'css' => [
                            'background' => 'rgba(0, 0, 0, 0.8)'
                        ]
                    ]
                ],
            ]
        ]);
?>


<div class="books-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= (yii::$app->session->hasFlash('create_ok')) ? '<p class="alert alert-success">'.yii::$app->session->getFlash('create_ok').'</p>' : '' ?>
    <?= (yii::$app->session->hasFlash('update_ok')) ? '<p class="alert alert-success">'.yii::$app->session->getFlash('update_ok').'</p>' : '' ?>

    <?= (yii::$app->session->hasFlash('create_bad')) ? '<p class="alert alert-danger">'.yii::$app->session->getFlash('create_bad').'</p>' : '' ?>
    <?= (yii::$app->session->hasFlash('update_bad')) ? '<p class="alert alert-danger">'.yii::$app->session->getFlash('update_bad').'</p>' : '' ?>

    <?= (yii::$app->session->hasFlash('delete_ok')) ? '<p class="alert alert-info">'.yii::$app->session->getFlash('delete_ok').'</p>' : '' ?>

    <?= (yii::$app->session->hasFlash('upload_preview_bad')) ? '<p class="alert alert-info">'.yii::$app->session->getFlash('upload_preview_bad').'</p>' : '' ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php // Pjax::begin();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => false,
        'headerRowOptions' => ['class'=>'books_table_header'],
        'tableOptions' => ['class'=>'table table-striped table-bordered books_table'],
        'columns' => [
            [
                'attribute' => 'id',
                'filterOptions' => ['class'=>'filter_id'],
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'preview',
                'format'=>'raw',
                'value' => function($model){
                    return Html::a(Html::img("/backend/web/img/small/".$model->preview, [
                                                'alt' => $model->name,
                                                'style' => 'height:50px;'
                                            ]),
                                        "/backend/web/img/original/".$model->preview,
                                        [ 'title' => $model->name, 'rel' => 'fancybox' ] );
                },
                'contentOptions' => ['class'=>'books_table_img'],
            ],
            [
                'attribute'=> 'author',
                'value'=> function($model) { return ( ($model->author_fullname) ? $model->author_fullname : Yii::t('app','UNKNOWN author')); }
            ],
            [
                'attribute' => 'date',
                'format' => ['date', 'php:d F Y'],
                'filter' => DatePicker::widget([
                                           'model' => $searchModel,
                                           'attribute' => 'date',
                                           'addon' => '',
                                           'language' => 'ru',
                                           'clientOptions' => [
                                               'autoclose' => true,
                                               'format' => 'dd/mm/yyyy',
                                           ]
                                       ])

            ],
            [
                'attribute' => 'date_create',
                'value' => function($model){ return $model->getBooksRelativeDate($model->date_create); },
                //'contentOptions' => ['title'=> function($model){ return $model->date_create;}],
                //'contentOptions' => ['title'=> $searchModel->date_create],
                'filter' => DatePicker::widget([
                                           'model' => $searchModel,
                                           'attribute' => 'date_create',
                                           'addon' => '',
                                           'language' => 'ru',
                                           'clientOptions' => [
                                               'autoclose' => true,
                                               'format' => 'dd/mm/yyyy',
                                           ]
                                       ])
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=> Yii::t('app', 'Action buttons'),
                'contentOptions' => ['class'=>'books_table_action'],
                'template' => '<table class="books_action_detail">
                                    <tbody>
                                        <tr>
                                            <td>{update}</td>
                                            <td>{view}</td>
                                            <td>{delete}{link}</td>
                                        </tr>
                                    </tbody>
                                </table>',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return '<a href="'.$url.'" title="'.Yii::t('app', 'Button view').'" aria-label="'.Yii::t('app', 'Button view').'" data-pjax="0" data-toggle="modal" data-target="#view_modal_'.$key.'">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </a>
                                <div class="modal fade" id="view_modal_'.$key.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog"><div class="modal-content"></div></div>
                                </div>';
                    },
                    'update' => function ($url, $model, $key) {
                        return '<a href="'.$url.'" title="'.Yii::t('app', 'Button update').'" aria-label="'.Yii::t('app', 'Button update').'" data-pjax="0" data-toggle="modal" data-target="#update_modal_'.$key.'">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                                <div class="modal fade" id="update_modal_'.$key.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog"><div class="modal-content"></div></div>
                                </div>';
                    },
                ]
            ],
        ],
    ]); ?>
    <?php // Pjax::end();?>

</div>
