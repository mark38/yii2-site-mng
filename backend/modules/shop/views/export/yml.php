<?php
/**
 * @var \yii\web\View $this
 */
use kartik\helpers\Html;

$this->title = 'Выгрузка на Яндекс-Маркет';
?>

<div>
    Ссылка для экспорта товаров: <?= Yii::$app->request->hostInfo.'/yml_catalog.xml'; ?> <?= Html::a('<i class="fa fa-external-link" aria-hidden="true"></i>', Yii::$app->request->hostInfo.'/yml_catalog.xml', ['target' => '_blank'])?>
</div>

