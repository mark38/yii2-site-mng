<?php

namespace backend\modules\gallery\controllers;

use Yii;
use yii\web\Controller;
use mark38\galleryManager\GalleryManagerAction;
use common\models\gallery\GalleryTypes;
use common\models\gallery\GalleryGroups;
use common\models\gallery\GalleryImages;

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

    public function actionList($action=null, $gallery_groups_id=null)
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
