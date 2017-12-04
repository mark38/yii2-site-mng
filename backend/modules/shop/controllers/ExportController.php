<?php

namespace app\modules\shop\controllers;

use common\models\main\Ancestors;
use common\models\main\Links;
use common\models\shop\ShopGoods;
use common\models\shop\ShopGroups;
use frontend\modules\shop\models\Helpers;
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

    public function actionYml($url=null)
    {
        return $this->render('yml');
    }

    public function actionYmlPriceList($url=null)
    {
        $url = $url ? $url : (Yii::$app->params['shop']['catalogUrl'] ? Yii::$app->params['shop']['catalogUrl'] : '/');

        $link = Links::findOne(['url' => $url, 'state' => true]);

        $goods = ShopGoods::find()
            ->innerJoinWith(['ancestorActiveGroup', 'link as productLink'])
            ->where(['ancestor_links_id' => $link->id, 'productLink.state' => true])
            ->orWhere(['shop_groups.links_id' => $link->id, 'productLink.state' => true]);

        $countGoods = $goods->count();
        if ($countGoods > 3000) {
            return $this->render('ymlExportLarge', ['countGoods' => $countGoods, 'size' => 3000]);
        } elseif ($countGoods == 0) {

        } else {

        }

        Yii::$app->layout = false;

        $goods = $goods->all();
        $categories = array();

        /** @var ShopGoods $good */
        foreach ($goods as $good) {
            if (!isset($categories[$good->shop_groups_id])) {
                $parent = ShopGroups::findOne(['links_id' => $good->shopGroup->link->parentLink->id]);
                $categories[$good->shop_groups_id] = [
                    'name' => $good->shopGroup->name,
                    'parentId' => ($parent ? $parent->id : null),
                ];

                $ancestors = Ancestors::find()->joinWith('ancestorLink')->where(['links_id' => $good->shopGroup->links_id])->orderBy(['links.seq' => SORT_DESC])->all();
                if (!$ancestors) continue;

                /** @var Ancestors $ancestor */
                foreach ($ancestors as $ancestor) {
                    $group = ShopGroups::findOne(['links_id' => $ancestor->ancestorLink->id]);

                    if ($group && !isset($categories[$group->id])) {
                        $parent = ShopGroups::findOne(['links_id' => $group->link->parentLink->id]);
                        $categories[$group->id] = [
                            'name' => $group->name,
                            'parentId' => ($parent ? $parent->id : null),
                        ];
                    }
                }
            }
        }

        return $this->render('ymlPriceList', [
            'categories' => $categories,
            'goods' => $goods
        ]);
    }
}
