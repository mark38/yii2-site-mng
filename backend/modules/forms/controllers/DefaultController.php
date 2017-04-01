<?php

namespace app\modules\forms\controllers;

use common\models\forms\FormFields;
use common\models\forms\Forms;
use common\models\forms\FormTypes;
use Yii;
use app\modules\items\models\ItemForm;
use yii\helpers\ArrayHelper;
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
    public function actionIndex($form_types_id=null)
    {
        $forms = Forms::find();
        if ($form_types_id) {
            $forms = $forms->where(['form_types_id' => $form_types_id]);
            $fields = FormFields::find()->select(['name'])->where(['form_types_id' => $form_types_id])->all();
        } else {
            $fields = FormFields::find()->select(['name'])->distinct()->all();
        }
        $forms = $forms->orderBy(['id' => SORT_DESC])->all();

        return $this->render('index', [
            'fields' => $fields,
            'forms' => $forms,
            'formTypes' => FormTypes::find()->orderBy(['name' => SORT_ASC])->all()
        ]);
    }

//    public function actionMng($form_types_id, $id)
//    {
//        $formType = FormTypes::findOne($form_types_id);
//        $formTypes = FormTypes::find()->orderBy(['name' => SORT_ASC])->all();
//
//        $form = Forms::findOne($id);
//
//        if ($form->load(Yii::$app->request->post()) && $form->save()) {
//
//            Yii::$app->getSession()->setFlash('success', 'Изменения приняты');
//            return $this->redirect(['mng', 'item_types_id' => $form->item_types_id, 'id' => $form->id]);
//        }
//
//        return $this->render('formForm', compact('formType', 'form', 'formTypes'));
//    }
//
//    public function actionItemDel($forms_id)
//    {
//        $item = Forms::findOne($forms_id);
//        if ($item) {
//            Yii::$app->getSession()->setFlash('success', 'Элемент удалён');
//            $item->delete();
//        }
//
//        return $this->redirect(['index']);
//    }
}
