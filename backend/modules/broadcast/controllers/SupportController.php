<?php

namespace app\modules\broadcast\controllers;

use common\models\broadcast\BroadcastLayouts;
use common\models\broadcast\BroadcastSend;
use Yii;
use common\models\broadcast\Broadcast;
use common\models\broadcast\BroadcastAddress;
use common\models\User;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Default controller for the `multicast` module
 */
class SupportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['readme'],
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


    public function actionReadme()
    {
        return $this->render('readme');
    }
}
