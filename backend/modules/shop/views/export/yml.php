<?php
/**
 * @var \yii\web\View $this
 */
use kartik\helpers\Html;

$this->title = 'Управление выгрузкой прайс-листов в Яндекс-Маркет';
?>

<div>
    Ссылка для экспорта товаров: <?= Yii::$app->request->hostInfo.'/mng/shop/export/yml-price-list'; ?> <?= Html::a('<i class="fa fa-external-link" aria-hidden="true"></i>', Yii::$app->request->hostInfo.'/mng/shop/export/yml-price-list', ['target' => '_blank'])?>
</div>

