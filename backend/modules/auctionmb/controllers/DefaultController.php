<?php

namespace app\modules\auctionmb\controllers;

use common\models\auctionmb\Auctionmb;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\main\Links;
use common\models\main\Contents;
use common\models\auctionmb\AuctionmbLotForm;
use common\models\auctionmb\AuctionmbLots;
use common\models\gallery\GalleryImagesForm;
use yii\web\UploadedFile;

/**
 * Default controller for the `auctionmb` module
 */
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
                        'actions' => ['index', 'lot-mng', 'lot-del', 'auction-activate'],
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

    public function actionIndex()
    {
        $auctionmbLots = AuctionmbLots::find()->joinWith('link')->orderBy(['seq' => SORT_ASC])->all();

        return $this->render('index', ['auctionmbLots' => $auctionmbLots]);
    }

    public function actionLotMng($id=null)
    {
        if ($id) {
            $auctionmbLot = AuctionmbLotForm::findOne($id);
            $link = Links::findOne($auctionmbLot->links_id);
            $auctionmbLot->text = $link->contents[0]->text;
        } else {
            $auctionmbLot = new AuctionmbLotForm();
            $link = new Links();
            $link->state = true;
        }

        $galleryImage = isset($link->gallery_images_id) ? GalleryImagesForm::findOne($link->gallery_images_id) : new GalleryImagesForm();
        $galleryImage->gallery_groups_id = Yii::$app->params['auctionmb']['galleryGroupsId'];

        if (Yii::$app->request->isPost) {
            $link->load(Yii::$app->request->post());
            $link->categories_id = Yii::$app->params['auctionmb']['categoriesId'];
            $link->layouts_id = Yii::$app->params['auctionmb']['layoutsId'];
            $link->views_id = Yii::$app->params['auctionmb']['viewsId'];
            $link->title = $link->title ? $link->title : $link->anchor;
            $link->save();

            $auctionmbLot->load(Yii::$app->request->post());
            $auctionmbLot->links_id = $link->id;
            $auctionmbLot->save();

            if ($auctionmbLot->id) {
                $content = Contents::findOne(['links_id' => $link->id, 'seq' => 1]);
                if (!$content) {
                    $content = new Contents();
                }
                $content->links_id = $link->id;
                $content->seq = 1;
                $content->text = $auctionmbLot->text;
                $content->save();

                $galleryImage->load(Yii::$app->request->post());
                $galleryImage->linksId = $link->id;
                $galleryImage->name = $link->name;
                $galleryImage->imageSmall = UploadedFile::getInstance($galleryImage, 'imageSmall');
                $galleryImage->imageLarge = UploadedFile::getInstance($galleryImage, 'imageLarge');
                $galleryImage->upload();
                $galleryImage->save();

                Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
                return $this->redirect(['lot-mng', 'id' => $auctionmbLot->id]);
            }
        }

        return $this->render('lotForm', compact('auctionmbLot', 'link', 'galleryImage'));
    }

    public function actionLotDel($id)
    {
        $lot = AuctionmbLots::findOne($id);
        if ($lot) {
            Links::findOne($lot->links_id)->delete();
            Yii::$app->session->setFlash('success', 'Запись удалена');
        } else {
            Yii::$app->session->setFlash('error', 'Запись уже удалена ранее');
        }

        return $this->redirect('index');
    }

    public function actionAuctionActivate($auctionmbLotsId=null)
    {
        $auction = Auctionmb::findOne(['auctionmb_lots_id' => $auctionmbLotsId, 'state' => true]);
        if ($auction) {
            Yii::$app->session->setFlash('error', 'Есть активный аукцион для данного лота.');
        } else {
            $auction = new Auctionmb();
            $auction->auctionmb_lots_id = $auctionmbLotsId;
            $auction->state = true;
            $auction->created_at = time();
            $auction->save();
            Yii::$app->session->setFlash('success', 'Аукцион для лота "'.AuctionmbLots::findOne($auctionmbLotsId)->link->anchor.'" запущен.');
        }

        return $this->redirect('index');
    }
}
