<?php

namespace app\modules\news\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use mark38\galleryManager\GalleryManagerAction;
use app\modules\news\models\NewsForm;
use common\models\main\Contents;
use common\models\main\Links;
use common\models\news\News;
use common\models\news\NewsTypes;

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
                        'actions' => ['index', 'mng', 'news-del'],
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

    public function actionIndex($news_id=null)
    {
        $news_type = Yii::$app->request->get('news_types_id') ? NewsTypes::findOne(Yii::$app->request->get('news_types_id')) : false;

        $link = new Links();
        $news = new News();

        if ($news_type) {
            $news->news_types_id = $news_type->id;
            $news->type_name = $news_type->name;
        }

        if ($news_id) {
            $news = News::findOne($news_id);
            $link = Links::findOne($news->links_id);
            $news->date = $news->date !== null ? date('d.m.Y', strtotime($news->date)) : null;
            $news->date_range = $news->date_from && $news->date_to ? date('d.m.Y', strtotime($news->date_from)).' - '.date('d.m.Y', strtotime($news->date_to)) : null;
            $news->full_text = Contents::findOne(['links_id' => $link->id, 'seq' => 1])->text;
            $news->prev_text = Contents::findOne(['links_id' => $link->id, 'seq' => 2])->text;
        } else {
            $link->state = true;
        }

        if (Yii::$app->request->isPost) {
            $link->load(Yii::$app->request->post());
            $link->parent = $news_type->links_id;
            $link->categories_id = $news_type->categories_id !== null ? $news_type->categories_id : $news_type->link->categories_id;
            $link->views_id = $news_type->views_id;
            $link->title = $link->title ? $link->title : $link->anchor;
            $link->save();

            $news->load(Yii::$app->request->post());
            $news->links_id = $link->id;
            $news->save();

            if ($news->id) {
                $content = Contents::findOne(['links_id' => $link->id, 'seq' => 1]);
                if (!$content) {
                    $content = new Contents();
                }
                $content->links_id = $link->id;
                $content->seq = 1;
                $content->text = $news->full_text;
                $content->save();
                $parent_conten_id = $content->id;

                $content = Contents::findOne(['parent' => $parent_conten_id, 'seq' => 2]);
                if (!$content) {
                    $content = new Contents();
                }
                $content->links_id = $link->id;
                $content->parent = $parent_conten_id;
                $content->seq = 2;
                $content->text = $news->prev_text;
                $content->save();
            }

            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
            return $this->redirect(['', 'news' => 'ch', 'news_types_id' => $news->news_types_id, 'news_id' => $news->id]);
        }

        $news_list = News::find()->orderBy(['date' => SORT_DESC])->all();

        return $this->render('index', [
            'news_list' => $news_list,
            'news_type' => $news_type,
            'news_types' => NewsTypes::find()->orderBy(['name' => SORT_ASC])->all(),
            'news' => $news,
            'link' => $link,
        ]);
    }

    public function actionMng($news_types_id, $id=null)
    {
        $news = $id ? NewsForm::findOne($id) : new NewsForm();

        return $this->render('newsForm', compact('news'));
    }

    public function actionNewsDel($links_id)
    {
        $link = Links::findOne($links_id);
        if ($link) {
            Yii::$app->getSession()->setFlash('success', 'Новость удалена');
            $link->delete();
        }

        return $this->redirect(['/news/index']);
    }
}
