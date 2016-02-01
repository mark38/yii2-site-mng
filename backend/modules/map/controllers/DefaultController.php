<?php

namespace backend\modules\map\controllers;

use common\models\main\Contents;
use Yii;
use common\models\main\Categories;
use common\models\main\Links;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLinks($categories_id=null)
    {
        $category = Categories::findOne($categories_id);
        $link = new Links();

        if (Yii::$app->request->get('mng_link') == 'ch') {
            $link = Links::findOne(Yii::$app->request->get('links_id'));
        }

        if ($link->load(Yii::$app->request->post()) && $link->save()) {
            $content = new Contents();
            $content->links_id = $link->id;
            $content->seq = 1;
            $content->save();

            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
        }

        return $this->render('links', [
            'category' => $category,
            'link' => $link,
        ]);
    }

    public function actionLinkDel($links_id, $categories_id=null)
    {
        $link = Links::findOne($links_id);
        if ($link) {
            Yii::$app->getSession()->setFlash('success', 'Ссылка удалена');
            $link->delete();
        }

        return $this->redirect(['/map/links', 'categories_id' => $categories_id]);
    }
}
