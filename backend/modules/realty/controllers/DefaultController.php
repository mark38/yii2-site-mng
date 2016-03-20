<?php

namespace app\modules\realty\controllers;

use common\models\realty\RealtyGroups;
use Yii;
use yii\web\Controller;
use common\models\realty\RealtyGoods;
use mark38\galleryManager\GalleryManagerAction;

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

    public function actionIndex($realty_groups_id=null, $id=null)
    {
        $realty_good = new RealtyGoods();
        if ($realty_groups_id) $realty_good->realty_groups_id = $realty_groups_id;
        if ($id) $realty_good = RealtyGoods::findOne($id);

        if ($realty_good->load(Yii::$app->request->post()) && $realty_good->save()) {
            $this->redirect(['', 'action' => 'ch', 'id' => $realty_good->id]);
            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
        }

        return $this->render('index', [
            'realty_groups' => RealtyGroups::find()->orderBy(['name' => SORT_ASC])->all(),
            'realty_good' => $realty_good,
            'realty_goods' => RealtyGoods::find()->innerJoinWith('realtyGroup')->orderBy(['realty_groups.name' => SORT_ASC])->all(),
        ]);
    }

}
