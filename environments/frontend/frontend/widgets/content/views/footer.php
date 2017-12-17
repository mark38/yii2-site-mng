<?php
use yii\bootstrap\Html;
use frontend\widgets\nav\Footer;
use common\models\geobase\GeobaseContact;

?>

<div class="container">
    <div class="row">
        <div class="col-lg-4 about">
            Сайт &laquo;Название&raquo;, 2011 &ndash; <?= date('Y')?> г.<br />
            <small>Официальный сайт</small>
        </div>
        <div class="col-lg-6">
            <?=Footer::widget([
                'link' => Yii::$app->view->params['links']['activeLink'],
                'linksTop' => Yii::$app->view->params['links']['top'],
                'linksHeader' => Yii::$app->view->params['links']['header'],
                'breadcrumbs' => Yii::$app->view->params['links']['breadcrumbs'],
            ])?>
        </div>
        <div class="col-lg-2 text-center">
            <div class="social-links">
                <small class="footer-text hidden-sm">Мы в соцсетях:</small>
                <ul class="list-inline social-nav">
                    <li><a href="http://vk.com/" target="_blank"><i class="fa fa-vk"></i></a></li>
                    <li><a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="http://ok.ru/" target="_blank"><i class="fa fa-odnoklassniki"></i></a></li>
                    <li><a href="https://www.instagram.com/" target="_blank"><i class="fa fa-instagram"></i></a></li>
                </ul>
            </div>

            <div><a href="/" class="logo" title="Название сайта"><img src="/assets/static/logo.svg" /></a></div>
        </div>
    </div>
</div>