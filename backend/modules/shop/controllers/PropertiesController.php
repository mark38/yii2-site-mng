<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;
use common\models\main\Contents;
use common\models\shop\ShopProperties;
use common\models\shop\ShopPropertyValues;
use mark38\galleryManager\GalleryManagerAction;

class PropertiesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
//        if (Yii::$app->request->isPost && Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'file')  $this->enableCsrfValidation = false;
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'gallery-manager' => [
                'class' => GalleryManagerAction::className(),
            ],
        ];
    }

    public function actionList($action=null, $properties_id=null, $values_id=null)
    {
        $property = null;
        $values = null;
        $value = null;
        $content = null;

        switch ($action) {
            case "property_add": $property = new ShopProperties(); break;
            case "property_ch": $property = ShopProperties::findOne($properties_id); break;
            case "get_values": $values = ShopPropertyValues::find()->where(['shop_properties_id' => $properties_id])->orderBy(['name' => SORT_ASC])->all(); break;
            case "value_add":
                $values = ShopPropertyValues::find()->where(['shop_properties_id' => $properties_id])->orderBy(['name' => SORT_ASC])->all();
                $value = new ShopPropertyValues();
                $value->shop_properties_id = $properties_id;
                $content = new Contents();
                break;
            case "value_ch":
                $values = ShopPropertyValues::find()->where(['shop_properties_id' => $properties_id])->orderBy(['name' => SORT_ASC])->all();
                $value = ShopPropertyValues::findOne($values_id);
                $content = $value->contents_id ? Contents::findOne($value->contents_id) : new Contents();
                if (!$content->seq) $content->seq = 1;
                break;
        }

        if (Yii::$app->request->isPost && $property && $property->load(Yii::$app->request->post()) && $property->save()) {
            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
            return $this->redirect(['', 'action' => 'property_ch', 'properties_id' => $property->id]);
        }

        if (Yii::$app->request->isPost && $value && $value->load(Yii::$app->request->post()) && $value->save()) {
            if ($content->load(Yii::$app->request->post()) && $content->save()) {
                $value->contents_id = $content->id;
                $value->update();
            }
            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
            $values = ShopPropertyValues::find()->where(['shop_properties_id' => $properties_id])->orderBy(['name' => SORT_ASC])->all();
            return $this->redirect(['', 'action' => 'value_ch', 'properties_id' => $properties_id, 'values_id' => $value->id]);
        }

        $properties = ShopProperties::find()->orderBy(['seq' => SORT_ASC])->all();

        return $this->render('main', compact('properties', 'property', 'values', 'value', 'content'));
    }

    public function actionPropertyDel($properties_id=null)
    {
        if (!$properties_id) {
            Yii::$app->getSession()->setFlash('error', 'Свойство не выбрано');
            return $this->redirect(['list']);
        }

        ShopProperties::findOne($properties_id)->delete();

        Yii::$app->getSession()->setFlash('success', 'Изменения приняты');

        return $this->redirect(['list']);
    }

    public function actionValueDel($properties_id=null, $values_id=null) {
        if (!$properties_id || !$values_id) {
            Yii::$app->getSession()->setFlash('error', 'Значение свойства не выбрано');
            return $this->redirect(['list']);
        }

        ShopPropertyValues::findOne($values_id)->delete();

        Yii::$app->getSession()->setFlash('success', 'Изменения приняты');

        return $this->redirect(['list', 'properties_id' => $properties_id, 'action' => 'get_values']);
    }
}
