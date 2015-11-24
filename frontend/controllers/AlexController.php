<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\TestForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Alex controller
 */
class AlexController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest(){
        return $this->render('test');
    }
}
