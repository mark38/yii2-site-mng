<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\shop\ShopCategories;

class CategoriesController extends Controller
{
    public function actionList($action=null, $id=null)
    {
        $type = null;
        $categories = new ActiveDataProvider([
            'query' => ShopCategories::find()->orderBy(['id' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        switch ($action) {
            case "add":
                $type = new ShopCategories();
                break;
            case "ch":
                if ($id) $type = ShopCategories::findOne($id);
                break;
        }

        if ($type && $type->load(Yii::$app->request->post()) && $type->save()) {
            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
            return $this->redirect('');
        }

        return $this->render('list', compact('action', 'type', 'categories'));
    }

    public function actionItemDel($items_id)
    {
        $item = ShopCategories::findOne($items_id);
        if ($item) {
            Yii::$app->getSession()->setFlash('success', 'Элемент удалён');
            $item->delete();
        }

        return $this->redirect(['list']);
    }
}
