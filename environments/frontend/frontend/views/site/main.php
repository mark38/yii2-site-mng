<?php
/**
 * @var $this \yii\web\View
 * @var $link \common\models\main\Links
 * @var $contents \common\models\main\Contents
 */
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;

$contents = ArrayHelper::index($contents, 'seq');

$this->title = $link->title;
if (!$link->start && $link->id != 1) {
    $this->params['breadcrumbs'][] = ['label' => 'Главная', 'url' => '/'];
    /** @var \common\models\main\Links $breadcrumb */
    foreach (Yii::$app->view->params['links']['breadcrumbs'] as $breadcrumb) {
        $this->params['breadcrumbs'][] = $link->id == $breadcrumb->id ? $breadcrumb->anchor : ['label' => $breadcrumb->anchor, 'url' => $breadcrumb->url];
    }
}

if (! $link->h1) $link->h1 = $link->anchor;

echo Html::tag('div', '<h1 class="h1 wow fadeIn">'.$link->h1.'</h1>', ['class' => 'container']);

$html = '';
/** @var \common\models\main\Contents $content */
foreach ($contents as $content) {
    if ($content->text) {
        $html .= $content->css_class ? $content->text : Html::tag('div', $content->text, ['class' => 'bg-w cnt-space wow fadeIn']);
    }
}

if ($html) {
    echo Html::tag('div', $html, ['class' => 'container']);
} else {
    echo Html::tag('div', '<em>Страница не заполнена, зайдите позднее.</em>', ['class' => 'text-center']);
}
?>
