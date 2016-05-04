<?php
use common\models\main\Modules;

/** @var $this \yii\web\View */

$items[] = [
    'label' => 'Меню управления сайтом',
    'options' => ['class' => 'header']
];
$items[] = [
    'label' => 'Карта сайта',
    'icon' => 'fa fa-sitemap',
    'url' => ['/map/index']
];

$modules = Modules::find()->where(['visible' => 1])->orderBy(['seq' => SORT_ASC])->all();
if ($modules) {
    $items[] = [
        'label' => 'Дополнительные модули',
        'options' => ['class' => 'header']
    ];
    foreach ($modules as $model) {
        $items[] = [
            'label' => $model->name,
            'icon' => $model->icon,
            'url' => [$model->url],
        ];
    }
}
?>

<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items
            ]
        ) ?>
    </section>

</aside>
