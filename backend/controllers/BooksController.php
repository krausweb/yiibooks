<?php

namespace backend\controllers;

use Yii;
use app\models\Books;
use app\models\BooksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{
    public function behaviors()
    {
        return [
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
            // @todo не смог свалидировать дату( - сделал примитивную проверку на html5 (в backend/views/books/_form.php)
            //if ( !$model->validate() ) return $this->redirect(['index']);

            // возвращаю к формату согласно БД
            $model->date_create = time();
            $model->date_update = time();
            $model->date = Yii::$app->formatter->asTimestamp($model->date);

            $model->save();
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
        if ( $model->load(Yii::$app->request->post())) {
            // @todo не смог запустить валидацию полей по дате в нужном формате - Нужный формат сразу вставляет Datepicker
            //if ( !$model->validate() ) return $this->redirect(['index']);

            $model->date_create = Yii::$app->formatter->asTimestamp($model->date_create);
            //$model->date_update = Yii::$app->formatter->asTimestamp($model->date_update);
            $model->date_update = time();
            $model->date = Yii::$app->formatter->asTimestamp($model->date);

            //$model->date_create = Yii::$app->formatter->asTimestamp(str_replace("/", "-", $model->date_create));
            //$model->date_update = Yii::$app->formatter->asTimestamp(str_replace("/", "-", $model->date_update));
            //$model->date = Yii::$app->formatter->asTimestamp(str_replace("/", "-", $model->date));

            $model->save();
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
        $this->findModel($id)->delete();
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
