<?php
use common\models\main\Categories;

/** @var $this \yii\web\View */

$items[] = ['label' => 'Категории сайта', 'options' => ['class' => 'header']];
foreach (Categories::find()->where(['visible' => 1])->orderBy(['seq' => SORT_ASC])->all() as $category) {
    $items[] = [
        'label' => $category->comment,
        'icon' => 'sitemap',
        'url' => ['/map/links', 'categories_id' => $category->id],
        'active' => $this->context->module->id == 'map' && Yii::$app->request->get('categories_id') == $category->id ? true : false,
    ];
}
$items[] = ['label' => 'Дополнительно', 'options' => ['class' => 'header']];
$items[] = ['label' => 'Редиректы', 'icon' => 'reply', 'url' => '#'];
$items[] = ['label' => 'Шаблоны', 'icon' => 'circle-thin', 'url' => '#'];
$items[] = ['label' => 'Виды', 'icon' => 'circle-thin', 'url' => '#'];

?>
<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items,
            ]
        ) ?>

    </section>

</aside>
