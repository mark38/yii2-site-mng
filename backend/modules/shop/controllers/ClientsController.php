<?php

namespace app\modules\shop\controllers;

use Yii;
use common\models\shop\ShopClientCarts;
use yii\bootstrap\Html;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class ClientsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['orders', 'order'],
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

    public function actionOrders()
    {
        $shopClientCarts = ShopClientCarts::find()->joinWith(['shopCart'])->where(['shop_carts.state' => false])->orderBy(['checkout_at' => SORT_DESC])->all();

        return $this->render('orders', ['shopClientCarts' => $shopClientCarts]);
    }

    public function actionOrder()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->layout = false;
        }

        if (!Yii::$app->request->post('id')) return false;

        $shopClientCart = ShopClientCarts::findOne(Yii::$app->request->post('id'));

        return [
            'success' => true,
            'head' => Html::tag('strong', date('d.m.Y H:i', $shopClientCart->shopCart->checkout_at)) . ($shopClientCart->shopClient->city ? ', заказ в г. '.$shopClientCart->shopClient->city : ''),
            'content' => $this->render('order', ['shopClientCart' => $shopClientCart])
        ];
    }
}
