<?php

namespace app\modules\gallery\controllers;

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
            ],
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

    public function actionIndexDef($action=null, $gallery_groups_id=null)
    {
        $gallery_types = GalleryTypes::find()->where(['visible' => 1])->orderBy(['comment' => SORT_ASC])->all();
        $gallery_groups = GalleryGroups::find()->innerJoinWith('galleryType')->where(['visible' => 1])->orderBy(['id' => SORT_DESC])->all();
        $gallery_group = false;

        if ($action) {
            if ($action == 'add') {
                $gallery_group = new GalleryGroups();
                $gallery_group->gallery_types_id = Yii::$app->request->get('gallery_types_id');
            } else if ($action == 'ch' && $gallery_groups_id) {
                $gallery_group = GalleryGroups::find()->where(['id' => $gallery_groups_id])->one();
            }

            if ($gallery_group->load(Yii::$app->request->post()) && $gallery_group->save()) {
                return $this->redirect(['', 'action' => 'ch', 'gallery_groups_id' => $gallery_group->id]);
            }
        }

        return $this->render('gallery', [
            'gallery_types' => $gallery_types,
            'gallery_groups' => $gallery_groups,
            'gallery_group' => $gallery_group,
        ]);
    }
}
