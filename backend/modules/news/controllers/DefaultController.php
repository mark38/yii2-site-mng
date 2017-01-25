<?php

namespace app\modules\news\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\modules\news\models\NewsForm;
use common\models\gallery\GalleryImagesForm;
use common\models\main\Contents;
use common\models\main\Links;
use common\models\news\News;
use common\models\news\NewsTypes;

class DefaultController extends Controller
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

    public function actionIndex($news_types_id=null)
    {
        $newsList = News::find();
        if ($news_types_id) {
            $newsList = $newsList->where(['news_types_id' => $news_types_id]);
        }
        $newsList = $newsList->orderBy(['date' => SORT_DESC])->all();

        return $this->render('index', [
            'newsList' => $newsList,
            'newsTypes' => NewsTypes::find()->orderBy(['name' => SORT_ASC])->all(),
        ]);
    }

    public function actionMng($news_types_id, $id=null)
    {
        $newsType = NewsTypes::findOne($news_types_id);

        if ($id) {
            $news = NewsForm::findOne($id);
            $link = Links::findOne($news->links_id);
        } else {
            $news = new NewsForm();
            $link = new Links();
            $link->state = true;
        }

        $news->news_types_id = $news_types_id;

        $galleryImage = isset($link->gallery_images_id) ? GalleryImagesForm::findOne($link->gallery_images_id) : new GalleryImagesForm();
        $galleryImage->gallery_groups_id = $newsType->gallery_groups_id;

        if (Yii::$app->request->isPost) {
            $link->load(Yii::$app->request->post());
            $link->parent = $newsType->links_id;
            $link->categories_id = $newsType->categories_id !== null ? $newsType->categories_id : $newsType->link->categories_id;
            $link->views_id = $newsType->views_id;
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

                $galleryImage->load(Yii::$app->request->post());
                $galleryImage->linksId = $link->id;
                $galleryImage->name = $link->name;
                $galleryImage->imageSmall = UploadedFile::getInstance($galleryImage, 'imageSmall');
                $galleryImage->imageLarge = UploadedFile::getInstance($galleryImage, 'imageLarge');
                $galleryImage->upload();
                $galleryImage->save();

                Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
                return $this->redirect(['mng', 'news_types_id' => $newsType->id, 'id' => $news->id]);
            }
        }

        return $this->render('newsForm', compact('newsType', 'link', 'news', 'galleryImage'));
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
