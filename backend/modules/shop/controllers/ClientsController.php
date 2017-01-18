<?php

namespace app\modules\shop\controllers;

use common\models\shop\ShopClientCarts;
use Yii;
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
                        'actions' => ['orders'],
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
}
