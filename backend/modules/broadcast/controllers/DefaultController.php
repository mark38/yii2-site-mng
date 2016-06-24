<?php

namespace app\modules\broadcast\controllers;

use app\modules\broadcast\models\BroadcastForm;
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
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'manager', 'render-send', 'send', 'address', 'status', 'layouts', 'layout-mng'],
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

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Broadcast::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionManager($id=null)
    {
        $broadcast = new BroadcastForm();
        if ($id) $broadcast = BroadcastForm::findOne($id);

        if ($broadcast->load(Yii::$app->request->post()) && $broadcast->save()) {
            Yii::$app->session->setFlash('success', 'Изменения приняты. Вы можете подготовить письмо к отправке.');
            return $this->redirect(Url::current(['id' => $broadcast->id]));
        }

        return $this->render('manager', [
            'broadcast' => $broadcast,
        ]);
    }

    public function actionRenderSend($broadcast_id)
    {
        $broadcast = Broadcast::findOne($broadcast_id);

        $broadcast_send = new BroadcastSend();
        $broadcast_send->broadcast_id = $broadcast_id;
        $broadcast_send->status = 0;
        $broadcast_send->save();

        if ($broadcast->registered_users == 1) {
            $users = User::find()->where(['status' => 10])->orderBy(['username' => SORT_ASC])->all();
            if ($users) {
                foreach ($users as $user) {
                    $broadcast_address = new BroadcastAddress();
                    $broadcast_address->broadcast_send_id = $broadcast_send->id;
                    $broadcast_address->user_id = $user->id;
                    $broadcast_address->save();
                }
            }
        }

        if ($broadcast->destinations) {
            $destinations = preg_split('/\,/', $broadcast->destinations);
            foreach ($destinations as $destination) {
                $arr = preg_split('/#/', $destination);
                $broadcast_address = new BroadcastAddress();
                $broadcast_address->broadcast_send_id = $broadcast_send->id;
                $broadcast_address->email = trim($arr[0]);
                $broadcast_address->fio = isset($arr[1]) ? trim($arr[1]) : '';
                $broadcast_address->save();
            }
        }

        return $this->redirect(['send', 'broadcast_send_id' => $broadcast_send->id]);
    }

    public function actionSend($broadcast_send_id, $action=null)
    {
        $broadcast_address = BroadcastAddress::find()->joinWith(['user'])->where(['broadcast_send_id' => $broadcast_send_id])->orderBy(['user.username' => SORT_ASC])->all();
        if ($action && $action == 'send') {
            exec('php '.Yii::getAlias('@app').'/../yii broadcast/default/send '.$broadcast_send_id.' > /dev/null 2>&1 &');
        }

        return $this->render('send', [
            'broadcast_address' => $broadcast_address
        ]);
    }

    public function actionAddress()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $broadcast_address = Yii::$app->request->post('broadcast_address');
            foreach ($broadcast_address as $index => $address) {
                if ($address['action'] == 'del') {
                    BroadcastAddress::deleteAll(['id' => $address['id']]);
                }
            }

            return [
                'success' => true,
            ];
        }
    }

    public function actionStatus()
    {
        $this->enableCsrfValidation = false;

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'success' => true,
                'address' => BroadcastAddress::find()->where(['broadcast_send_id' => Yii::$app->request->post('broadcast_send_id')])->all()
            ];
        }
    }

    public function actionLayouts()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BroadcastLayouts::find()->orderBy(['name' => SORT_ASC])
        ]);

        return $this->render('layouts', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionLayoutMng($id=null)
    {
        $layout = new BroadcastLayouts();
        if ($id) $layout = BroadcastLayouts::findOne($id);

        if ($layout->load(Yii::$app->request->post()) && $layout->save()) {
            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
            return $this->redirect(['/boradcast/ssss-mng', 'id' => $id]);
        }

        return $this->render('layoutMng', [
            'layout' => $layout
        ]);
    }
}
