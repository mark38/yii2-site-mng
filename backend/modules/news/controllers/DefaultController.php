<?php

namespace backend\modules\news\controllers;

use common\models\main\Contents;
use common\models\main\Links;
use common\models\news\News;
use Yii;
use yii\web\Controller;
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

    public function actionList($id=null)
    {
        $news_list = News::find()->orderBy(['date' => SORT_ASC])->all();

        $link = new Links();
        $news = new News();
        $prev_news = new Contents();
        $full_news = new Contents();

        return $this->render('news', [
            'news_list' => $news_list,
            'news' => $news,
            'link' => $link,
            'prev_news' => $prev_news,
            'full_news' => $full_news,
        ]);
    }
}
