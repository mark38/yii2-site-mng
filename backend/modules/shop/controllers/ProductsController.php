<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\models\main\Links;
use common\models\shop\ShopGroups;
use yii\web\Response;
use backend\modules\shop\models\LinkGroupForm;
use backend\modules\shop\models\GoodForm;
use backend\modules\shop\models\GroupForm;
use backend\modules\shop\models\LinkGoodForm;
use backend\widgets\map\ProductLinks;

class ProductsController extends Controller
{
    /**
     * @param $id
     * @param $type ['group', 'good']
     * @return string
     */
    public function actionLinks($action=null, $id=null, $parent=null, $type=null)
    {
        $catalogLink = Links::findOne(['url' => Yii::$app->params['shop']['catalogUrl']]);

        $link = false;
        $group = false;
        $good = false;

        if ($action) {
            switch ($type) {
                case "group":
                    if ($id) {
                        $link = LinkGroupForm::findOne($id);
                        $group = GroupForm::findOne(['links_id' => $id]);
                        $group->groupProperties = ArrayHelper::getColumn($group->shopGroupProperties, 'shop_properties_id');
                    } else {
                        $link = new LinkGroupForm();
                        $group = new GroupForm();
                        $group->groupProperties = ArrayHelper::getColumn($group->shopGroupProperties, 'shop_properties_id');
                    }
                    break;
                case "good":
                    $group = ShopGroups::findOne(['links_id' => $parent]);
                    if ($id) {
                        $link = LinkGoodForm::findOne($id);
                        $good = GoodForm::findOne(['links_id' => $id]);
                        $link->state = $good->state;
                    } else {
                        $link = new LinkGoodForm();
                        $good = new GoodForm();

                    }
                    $link->parent = $parent;
                    $good->shop_groups_id = $group->id;
                    break;
            }

            if (Yii::$app->request->post()) {
                $link->load(Yii::$app->request->post());
                $link->save();

                switch ($type) {
                    case "group":
                        $group->load(Yii::$app->request->post());
                        $group->links_id = $link->id;
                        $group->name = !$group->id ? $link->anchor : $group->name;
                        $group->save();
                        break;
                    case "good":
                        $good->load(Yii::$app->request->post());
                        $good->links_id = $link->id;
                        $good->name = !$good->id ? $link->anchor : $good->name;
                        $good->state = $link->state;
                        $good->save();
                        break;
                }
            }
        }

        return $this->render('links', compact('action', 'type', 'catalogLink', 'link', 'group', 'good'));
    }

    public function actionGetChildren()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) Yii::$app->response->format = Response::FORMAT_JSON;

        $this->layout = false;

        return [
            'success' => true,
            'content' => ProductLinks::widget([
                'categoriesId' => Yii::$app->params['shop']['categoriesId'],
                'parent' => $request->post('parent')
            ])
        ];
    }
}
