<?php

namespace backend\modules\gallery\controllers;

use yii\web\Controller;
use common\models\gallery\GalleryTypes;
use common\models\gallery\GalleryGroups;
use common\models\gallery\GalleryImages;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $gallery_types = GalleryTypes::find()->where(['visible' => 1])->orderBy(['comment' => SORT_ASC])->all();

        return $this->render('gallery', [
            'gallery_types' => $gallery_types,
        ]);
    }
}
