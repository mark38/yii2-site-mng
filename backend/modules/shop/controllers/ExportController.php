<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ExportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error', 'yml-price-list'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['yml'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionYml()
    {
        return $this->render('yml');
    }

    public function actionYmlPriceList()
    {
        Yii::$app->layout = false;

        return $this->render('ymlPriceList');
    }
}
