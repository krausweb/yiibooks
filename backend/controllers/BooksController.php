<?php

namespace backend\controllers;

use Yii;
use app\models\Books;
use app\models\BooksSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' =>[
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Books models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();
        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Books model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Books();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate()){
                yii::$app->session->setFlash('create_bad', yii::t('app','New book not has been created'));
                return $this->redirect(['index']);
            }

            // upload new preview
            $model->upload_preview = UploadedFile::getInstance($model, 'upload_preview');
            if($model->upload_preview) {
                if (($model->upload_preview->type == 'image/jpeg' or $model->upload_preview->type == 'image/png'
                        or $model->upload_preview->type == 'image/gif' or $model->upload_preview->type == 'image/svg+xml')
                    and $model->upload_preview->size < 999999
                ) {
                    $preview_name_arr = preg_replace("~[ :-]~", "_", explode(".", $model->upload_preview->name));
                    $preview_name     = $preview_name_arr[0] . '__' . time() . "." . $model->upload_preview->extension;
                    $model->upload_preview->saveAs('img/small/' . $preview_name, false);
                    $model->upload_preview->saveAs('img/original/' . $preview_name);
                    $model->preview = $preview_name;
                }else{
                    yii::$app->session->setFlash('upload_preview_bad', yii::t('app', 'Preview not uploaded: error type or big size'));
                }
            }

            // возвращаю к формату согласно БД
            $model->date_create = time();
            $model->date_update = time();
            $model->date        = Yii::$app->formatter->asTimestamp($model->date);

            $model->save();
            yii::$app->session->setFlash('create_ok', yii::t('app', 'New book - <i>{name}</i> - has been successfully created', array('name' => $model->name)));

            return $this->redirect(Url::previous());

        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $name_previous = $model->name; // for only !validate()
        if ( $model->load(Yii::$app->request->post())) {
            if ( !$model->validate() ){
                yii::$app->session->setFlash('update_bad', yii::t('app', 'Book - <i>{name}</i> - not has been updated', array('name' => $name_previous)));
                return $this->redirect(['index']);
            }

            // upload new preview
            $model->upload_preview = UploadedFile::getInstance($model, 'upload_preview');
            if($model->upload_preview) {
                if (($model->upload_preview->type == 'image/jpeg' or $model->upload_preview->type == 'image/png'
                        or $model->upload_preview->type == 'image/gif' or $model->upload_preview->type == 'image/svg+xml')
                    and $model->upload_preview->size < 999999
                ) {
                    $preview_name_arr = preg_replace("~[ :-]~", "_", explode(".", $model->upload_preview->name));
                    $preview_name     = $preview_name_arr[0] . '__' . time() . "." . $model->upload_preview->extension;
                    $model->upload_preview->saveAs('img/small/' . $preview_name, false);
                    $model->upload_preview->saveAs('img/original/' . $preview_name);
                    $model->preview = $preview_name;
                }else{
                    yii::$app->session->setFlash('upload_preview_bad', yii::t('app', 'Preview not uploaded: error type or big size'));
                }
            }

            $model->date_create = Yii::$app->formatter->asTimestamp($model->date_create);
            //$model->date_update = Yii::$app->formatter->asTimestamp($model->date_update);
            $model->date_update = time();
            $model->date = Yii::$app->formatter->asTimestamp($model->date);

            $model->save();
            yii::$app->session->setFlash('update_ok', yii::t('app', 'Book - <i>{name}</i> - has been successfully updated', array('name' => $model->name)));
            return $this->redirect(Url::previous());

        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $data = $this->findModel($id);
        $this->findModel($id)->delete();
        yii::$app->session->setFlash('delete_ok', yii::t('app', 'Book - <i>{name}</i> - was deleted', array('name' => $data->name)));

        return $this->redirect( Url::previous() );
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
