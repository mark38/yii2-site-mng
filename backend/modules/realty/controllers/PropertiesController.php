<?php

namespace app\modules\realty\controllers;

use common\models\realty\RealtyPropertyValues;
use Yii;
use yii\web\Controller;
use common\models\realty\RealtyProperties;

class PropertiesController extends Controller
{
    public function actionIndex($id=null, $realty_property_values_id=null)
    {
        $property = new RealtyProperties();
        $property_value = false;
        if ($id) {
            $property = RealtyProperties::findOne($id);
            $property_value = new RealtyPropertyValues();
            if ($realty_property_values_id) {
                $property_value = RealtyPropertyValues::findOne($realty_property_values_id);
            }

            if ( $property_value->load(Yii::$app->request->post()) ) {
                $property_value->realty_properties_id = $id;
                $property_value->save();
                $property_value = new RealtyPropertyValues();
            }
        }

        if ($property->load(Yii::$app->request->post()) && $property->save()) {
            $this->redirect(['', 'action' => 'ch', 'id' => $property->id]);
            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
        }

        return $this->render('properties', [
            'property' => $property,
            'properties' => RealtyProperties::find()->orderBy(['seq' => SORT_ASC])->all(),
            'property_value' => $property_value,
        ]);
    }

    public function actionDelete($id=null)
    {
        if ($id) {
            RealtyProperties::deleteAll(['id' => $id]);
            Yii::$app->getSession()->setFlash('success', 'Удалено');
        }

        return $this->redirect(['index']);
    }

    public function actionDeletePropertyValue($id, $realty_property_values_id)
    {
        if ($realty_property_values_id) {
            RealtyPropertyValues::deleteAll(['id' => $realty_property_values_id]);
        }

        return $this->redirect(['index', 'action' => 'ch', 'id' => $id]);
    }
}
