<?php

namespace app\modules\user\controllers;

use app\modules\user\models\ProfileForm;
use Yii;
use common\models\User;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Default controller for the `user` module
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
                        'actions' => ['index', 'profile'],
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProfile($user_id=false)
    {
        $profile = new ProfileForm();
        $profile->user_id = $user_id ? $user_id : Yii::$app->user->id;

        if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
            Yii::$app->session->setFlash('success', 'Изменения приняты');
            return $this->redirect(['profile', 'user_id' => $profile->user_id]);
        } else {
            $user = User::findOne($profile->user_id);
            $profile->username = $user->username;
            $profile->email = $user->email;
        }

        return $this->render('myProfile', ['profile' => $profile]);
    }
}
