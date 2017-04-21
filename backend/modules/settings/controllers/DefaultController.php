<?php

namespace app\modules\settings\controllers;

use app\modules\user\models\ProfileForm;
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
            $user = new ProfileForm();
        } elseif ($id) {
            $user = ProfileForm::find()->where(['id' => $id])->one();
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
        return $this->redirect('index');
    }

    public function actionRoles($name = null, $new = null)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthItem::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if ($new) {
            $role = new AuthItem();
        } elseif ($name) {
            $role = AuthItem::find()->where(['name' => $name])->one();
        }

        if (isset($role) && $role->load(Yii::$app->request->post()) && $role->save()) {
            if ($name) {
                Yii::$app->getSession()->setFlash('success', 'Информация о группе обновлена.');
            } else {
                Yii::$app->getSession()->setFlash('success', 'Группа добавлена в систему');
            }
        } else {
            return $this->render('roles', [
                'dataProvider' => $dataProvider,
                'role' => isset($role) ? $role : ''
            ]);
        }
        return $this->redirect('roles');
    }

    public function actionDelRole($name) {
        $role = AuthItem::find()->where(['name' => $name])->one();
        if ($role->name != 'admin' && $role->delete()) {
            Yii::$app->getSession()->setFlash('success', 'Группа была удалена');
        } else {
            Yii::$app->getSession()->setFlash('danger', 'Что-то пошло не так. Вы не можете удалить админа');
        }
        return $this->redirect('roles');
    }
}
