<?php

namespace app\modules\gallery\controllers;

use backend\widgets\gallery\GalleryManager;
use Yii;
use yii\web\Controller;
use common\models\gallery\GalleryTypes;
use common\models\gallery\GalleryGroups;
use backend\widgets\gallery\GalleryManagerAction;

class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'gallery-manager' => [
                'class' => GalleryManagerAction::className(),
            ]
        ];
    }

    public function actionIndex($gallery_types_id=null)
    {
        $galleryTypes = GalleryTypes::find()->where(['visible' => 1])->orderBy(['comment' => SORT_ASC])->all();
        $galleryGroups = GalleryGroups::find()->innerJoinWith(['galleryType'])->where(['gallery_types.visible' => true]);
        if ($gallery_types_id !== null) $galleryGroups = $galleryGroups->andWhere(['gallery_types_id' => $gallery_types_id]);
        $galleryGroups = $galleryGroups->all();

        return $this->render('index', compact('galleryTypes', 'galleryGroups'));
    }

    public function actionMng($gallery_types_id, $gallery_groups_id=null)
    {
        $galleryType = GalleryTypes::findOne($gallery_types_id);
        $galleryGroup = new GalleryGroups();
        $galleryGroup->gallery_types_id = $gallery_types_id;
        if ($gallery_groups_id) {
            $galleryGroup = GalleryGroups::findOne($gallery_groups_id);
        }

        if ($galleryGroup->load(Yii::$app->request->post()) && $galleryGroup->save()) {
            return $this->redirect(['', 'gallery_types_id' => $gallery_types_id, 'gallery_groups_id' => $galleryGroup->id]);
        }

        return $this->render('mngGallery', compact('galleryType', 'galleryGroup'));
    }

    public function actionGroupDel($gallery_groups_id)
    {
        $galleryGroup = GalleryGroups::findOne($gallery_groups_id);
        $galleryTypesId = null;
        if ($galleryGroup) {
            $galleryTypesId = $galleryGroup->gallery_types_id;
            $galleryGroup->delete();
        }

        Yii::$app->getSession()->setFlash('success', 'Галерея удалена');
        return $this->redirect(['index', 'gallery_types_id' => $galleryTypesId]);
    }
}
