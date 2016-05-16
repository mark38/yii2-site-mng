<?php

namespace app\modules\certificates\controllers;

use Yii;
use common\models\certificates\Certificates;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Default controller for the `certificate` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList($id = null, $new = null)
    {
        $certificate = $new ? new Certificates() : Certificates::findOne($id);
        $certificates = new ActiveDataProvider([
            'query' => Certificates::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if ($certificate && $certificate->load(Yii::$app->request->post()) && $certificate->save()) {
            if ($new) {
                Yii::$app->getSession()->setFlash('success', 'Справка добавлена');
            } else {
                Yii::$app->getSession()->setFlash('success', 'Изменения сохранены');
            }

        } else {
            return $this->render('list', [
                'certificates' => $certificates,
                'certificate' => $certificate,
                'new' => $new
            ]);
        }
        return $this->redirect(['/certificates/list']);
    }

    public function actionDelCertificate($id) {
        $certificate = Certificates::findOne($id);
        if ($certificate->delete()) {
            Yii::$app->getSession()->setFlash('success', 'Справка удалена');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Возникли проблемы. Попробуйте позже.');
        }
        return $this->redirect(['/certificates/list']);
    }
}
