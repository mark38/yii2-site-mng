<?php

namespace backend\modules\map\controllers;

use Yii;
use yii\web\Controller;
use common\models\main\Categories;
use common\models\main\Links;
use common\models\main\Contents;
use backend\widgets\gallery\GalleryManagerAction;

class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'gallery-manager' => [
                'class' => GalleryManagerAction::className(),
            ],
        ];
    }

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
            if (!$link->contents) {
                $content = new Contents();
                $content->links_id = $link->id;
                $content->seq = 1;
                $content->save();
            }
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

    public function actionContent($links_id, $categories_id=null)
    {
        $link = Links::findOne($links_id);
        $contents = Contents::find()->where(['links_id' => $links_id])->orderBy(['seq' => SORT_ASC])->all();

        if (Yii::$app->request->post()) {
            foreach ($contents as $index => $content) {
                if (Yii::$app->request->post('content-'.$index)) {
                    echo Yii::$app->request->post('content-'.$index);
                    $contents[$index]->text = Yii::$app->request->post('content-'.$index);
                    $contents[$index]->save();

                    /*$contents[$index]->load(Yii::$app->request->post());
                    $contents[$index]->save();*/
                }
            }
        }

        return $this->render('content', [
            'link' => $link,
            'contents' => $contents
        ]);
    }
}
