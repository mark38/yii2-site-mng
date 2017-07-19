<?php

namespace app\modules\items\controllers;

use Yii;
use app\modules\items\models\TypeForm;
use common\models\items\ItemTypes;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class TypesController extends Controller
{
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
                        'actions' => ['index'],
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

    public function actionIndex($action=null, $id=null)
    {
        $type = null;
        $types = new ActiveDataProvider([
            'query' => ItemTypes::find()->orderBy(['name' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        switch ($action) {
            case "add":
                $type = new TypeForm();
                break;
            case "ch":
                if ($id) $type = TypeForm::findOne($id);
                break;
        }

        if ($type && $type->load(Yii::$app->request->post()) && $type->save()) {
            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
            return $this->redirect('');
        }

        return $this->render('index', compact('action', 'type', 'types'));
    }
}