<?php
use yii\bootstrap\Html;
use common\models\main\Categories;

/** @var $this \yii\web\View */

$categories_items = array();
foreach (Categories::find()->orderBy(['seq' => SORT_ASC])->all() as $category) {
    $categories_items[] = [
        'label' => $category->comment,
        'icon' => 'fa fa-circle-o',
        'url' => ['/map/links', 'categories_id' => $category->id],
        'active' => $this->context->module->id == 'map' && Yii::$app->request->get('categories_id') == $category->id ? true : false,
    ];
}

?>
<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Меню управления сайтом', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Карта сайта',
                        'icon' => 'fa fa-sitemap',
                        'url' => '#',
                        'items' => $categories_items,
                    ],
                    ['label' => 'Редиректы', 'icon' => 'fa fa-reply', 'url' => ['/redirects']],
                    ['label' => 'Дополнительные модули', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Новости',
                        'icon' => 'fa fa-newspaper-o',
                        'url' => ['/news/list'],
                        'active' => $this->context->module->id == 'news' ? true : false,
                    ],
                    ['label' => 'Menu Yii2 framework', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
