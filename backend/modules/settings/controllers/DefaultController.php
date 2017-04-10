<?php

namespace app\modules\settings\controllers;

use common\models\rbac\AuthItem;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;

/**
 * Default controller for the `settings` module
 */
class DefaultController extends Controller
{
    public function actionIndex($id = null, $new = null)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $roles = ArrayHelper::map(AuthItem::find()->all(), 'name', 'name');

        if ($new) {
            $user = new User();
        } elseif ($id) {
            $user = User::find()->where(['id' => $id])->one();
        }

        if (isset($user) && $user->load(Yii::$app->request->post()) && $user->save()) {
            if ($id) {
                Yii::$app->getSession()->setFlash('success', 'Информация о пользователе обновлена.');
            } else {
                Yii::$app->getSession()->setFlash('success', 'Пользователь добавлен в систему');
            }
        } else {
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'user' => isset($user) ? $user : '',
                'roles' => $roles
            ]);
        }
        return $this->redirect('/settings');
    }
}
