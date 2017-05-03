<?php

namespace app\modules\items\controllers;

use Yii;
use app\modules\items\models\ItemForm;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\main\Contents;
use common\models\gallery\GalleryImagesForm;
use common\models\items\Items;
use common\models\items\ItemTypes;

/**
 * Default controller for the `items` module
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
                        'actions' => ['index', 'mng', 'item-del'],
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

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($item_types_id=null)
    {
        $items = Items::find();
        if ($item_types_id) {
            $items = $items->where(['item_types_id' => $item_types_id]);
        }
        $items = $items->orderBy(['item_types_id' => SORT_ASC, 'items.seq' => SORT_ASC])->all();
        
        return $this->render('index', [
            'items' => $items,
            'itemTypes' => ItemTypes::find()->orderBy(['name' => SORT_ASC])->all()
        ]);
    }

    public function actionMng($item_types_id, $id=null)
    {
        $itemType = ItemTypes::findOne($item_types_id);
        $itemTypes = ItemTypes::find()->orderBy(['name' => SORT_ASC])->all();

        if ($id) {
            $item = ItemForm::findOne($id);
        } else {
            $item = new ItemForm();
            $item->item_types_id = $item_types_id;
        }

        $galleryImage = isset($item->gallery_images_id) ? GalleryImagesForm::findOne($item->gallery_images_id) : new GalleryImagesForm();
        if ($itemType->gallery_groups_id) {
            $galleryImage->gallery_groups_id = $itemType->gallery_groups_id;
        } else {
            $galleryImage = false;
        }

        if ($item->load(Yii::$app->request->post()) && $item->save()) {
            $updateContent = false;
            $content = false;
            if ($item->contents_id) {
                $content = Contents::findOne($item->contents_id);
                $updateContent = true;
            } else if ($item->text){
                $content = new Contents();
                $updateContent = true;
            }

            if ($updateContent && $content) {
                $content->text = $item->text;
                $content->save();
                if (!$item->contents_id) {
                    $item->contents_id = $content->id;
                    $item->update();
                }
            }

            if ($galleryImage) {
                $galleryImage->load(Yii::$app->request->post());
                $galleryImage->name = $item->name;
                $galleryImage->imageSmall = UploadedFile::getInstance($galleryImage, 'imageSmall');
                $galleryImage->imageLarge = UploadedFile::getInstance($galleryImage, 'imageLarge');
                $galleryImage->upload();
                $galleryImage->save();

                $item->gallery_images_id = $galleryImage->id;
                $item->update();
            }

            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
            return $this->redirect(['mng', 'item_types_id' => $item->item_types_id, 'id' => $item->id]);
        }

        return $this->render('itemForm', compact('itemType', 'item', 'galleryImage', 'itemTypes'));
    }

    public function actionItemDel($items_id)
    {
        $item = Items::findOne($items_id);
        if ($item) {
            Yii::$app->getSession()->setFlash('success', 'Элемент удалён');
            $item->delete();
        }

        return $this->redirect(['index']);
    }
}
