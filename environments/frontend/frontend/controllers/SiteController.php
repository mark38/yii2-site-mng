<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\NavLinks;
use frontend\models\SignupForm;
use Yii;
use yii\bootstrap\Html;
use yii\web\Controller;
use frontend\models\Widget;
use common\models\main\Links;
use common\models\main\Redirects;
use common\models\main\Contents;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function init()
    {
        if (!Yii::$app->session->isActive) Yii::$app->session->open();
    }

    public function actionCatch($url=null)
    {
        $link = Links::findOne(['url' => '/'.$url]);
        if (!$link) {
            if ($redirect = Redirects::findOne(['url' => '/'.$url])) {
                $code = $redirect->code ? $redirect->code : 301;
                return $this->redirect($redirect->link->url, $code);
            }

            $link = Links::findOne(['url' => '/404']);
        } else {
            if ($redirect = Redirects::findOne(['url' => '/'.$url])) return $this->redirect(Links::findOne($redirect->links_id)->url, $redirect->code);

            if ($link->state == 0) $link = Links::findOne(['url' => '/404']);
        }

        $this->layout = $link->layout->name;

        if ($link->description) Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $link->description]);
        if ($link->keywords) Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $link->keywords]);

        if (isset($link->id)) {
            $contents = Contents::find()->where(['links_id' => $link->id])->orderBy(['seq' => SORT_ASC])->all();
            for ($i=0; $i<count($contents); $i++) {
                $model = new Widget();

                $contents[$i]->text = preg_replace_callback('/(\{{)(.+)(}})/', function($match) use ($model, $link) {return $model->renderModule($link, $match);}, $contents[$i]->text);
                $contents[$i]->text = preg_replace_callback('/(\[\[)(.+)(]])/', function($match) use ($model, $link) {return $model->renderWidget($link, $match);}, $contents[$i]->text);

                if ($contents[$i]->css_class) $contents[$i]->text = Html::tag('div', $contents[$i]->text, ['class' => $contents[$i]->css_class]);
            }
        }

        foreach ($contents as & $content) {
//            $content->text = preg_replace("#<p(\s*?)>|</p>#is", '', $content->text);
//            $content->text = preg_replace("#<p(\s*?)></p>#is", '', $content->text);
        }

        Yii::$app->view->params['links'] = (new NavLinks())->getLinks($link);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->layout = false;
        }

        return $this->render($link->view->name, [
            'link' => $link,
            'contents' => $contents
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
