<?php
use yii\bootstrap\Html;
use frontend\widgets\nav\Top;
use frontend\widgets\nav\Header;

?>

<header>
    <div class="container">
        <?= Top::widget([
            'link' => Yii::$app->view->params['links']['activeLink'],
            'links' => Yii::$app->view->params['links']['top'],
            'breadcrumbs' => Yii::$app->view->params['links']['breadcrumbs'],
        ]) ?>
        <div class="cnt-header row">
            <div class="col-xs-2 hidden-sm hidden-md hidden-lg"></div>
            <div class="col-lg-3 col-xs-6"><a href="/" class="logo-link" title="Мир дверей - интернет-магазин"><img src="/assets/static/logo.svg" alt="МирДверей" class="logo" /></a></div>
            <div class="col-lg-6 hidden-xs">

            </div>

        </div>
    </div>
    <?= Header::widget([
        'link' => Yii::$app->view->params['links']['activeLink'],
        'links' => Yii::$app->view->params['links']['header'],
        'breadcrumbs' => Yii::$app->view->params['links']['breadcrumbs'],
    ]) ?>
</header>
