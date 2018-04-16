<?php

namespace app\modules\shop\controllers;

use common\models\gallery\GalleryGroups;
use common\models\shop\ShopGoodGallery;
use Yii;
use common\models\gallery\GalleryImagesForm;
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
use backend\widgets\gallery\GalleryManagerAction;

class ProductsController extends Controller
{
    public function actions()
    {
        return [
            parent::actions(),
            'gallery-manager' => [
                'class' => GalleryManagerAction::className(),
            ],
        ];
    }

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
        $galleryImage = false;
        $galleryGroup = false;

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
                    $groupParent = ShopGroups::findOne(['links_id' => $parent]);
                    if ($groupParent) $group->parent_id = $groupParent->id;
                    break;
                case "good":
                    $group = ShopGroups::findOne(['links_id' => $parent]);
                    if ($id) {
                        $link = LinkGoodForm::findOne($id);
                        $good = GoodForm::findOne(['links_id' => $id]);

                        $galleryGroup = GalleryGroups::findOne([
                            ShopGoodGallery::findOne([
                                'shop_goods_id' => $good->id,
                                'gallery_types_id' => Yii::$app->params['shop']['gallery']['good'],
                            ])->gallery_groups_id,
                        ]);
                    } else {
                        $link = new LinkGoodForm();
                        $good = new GoodForm();
                        $link->parent = $parent;
                    }
                    $good->shop_groups_id = $group->id;
                    $galleryImage = $link->gallery_images_id ? GalleryImagesForm::findOne($link->gallery_images_id) : new GalleryImagesForm();
                    break;
            }

            if (Yii::$app->request->post()) {
                $link->load(Yii::$app->request->post());
                $link->save();

                switch ($type) {
                    case "group":
                        $group->load(Yii::$app->request->post());
                        $group->links_id = $link->id;
//                        $group->name = !$group->id ? $link->anchor : $group->name;
                        $group->name = $link->anchor;
                        $group->save();
                        break;
                    case "good":
                        $good->load(Yii::$app->request->post());
                        $good->links_id = $link->id;
//                        $good->name = !$good->id ? $link->anchor : $good->name;
                        $good->name = $link->anchor;
                        $good->state = $link->state;
                        $good->save();

                        return $this->redirect(['', 'action' => 'ch', 'id' => $link->id, 'parent' => $parent, 'type' => 'good']);

//                        $galleryImage->load(Yii::$app->request->post());
//                        if (!$galleryImage->gallery_groups_id) {
//                            $galleryGroup = new GalleryGroups();
//                            $galleryGroup->gallery_types_id = Yii::$app->params['shop']['gallery']['good'];
//                            $galleryGroup->name = $link->anchor;
//                            $galleryGroup->save();
//                            $galleryImage->gallery_groups_id = $galleryGroup->id;
//                        }
//                        $galleryImage->linksId = $link->id;
//                        $galleryImage->name = $link->anchor;
//                        $galleryImage->imageSmall = UploadedFile::getInstance($galleryImage, 'imageSmall');
//                        $galleryImage->imageLarge = UploadedFile::getInstance($galleryImage, 'imageLarge');
//                        $galleryImage->upload();
//                        $galleryImage->save();
                        break;
                }
            }
        }

        return $this->render('links', compact('action', 'type', 'catalogLink', 'link', 'group', 'good', 'galleryGroup', 'galleryImage'));
    }

    public function actionLinkDel($id)
    {
        $link = Links::findOne($id);

        $product = GoodForm::find()->where(['links_id' => $link->id])->one();
        if ($product) $product->delete();

        if ($link) {
            Yii::$app->session->setFlash('success', 'Запись удалена');
            $link->delete();
        }

        return $this->redirect(['links']);
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
