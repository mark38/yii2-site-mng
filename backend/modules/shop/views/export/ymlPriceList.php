<?php
/**
 * @var \yii\web\View $this
 */
use kartik\helpers\Html;

$this->title = 'Управление выгрузкой прайс-листов в Яндекс-Маркет';

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
    '<yml_catalog date="'.date('Y-m-d H:i').'">' . PHP_EOL .
    '    <shop>' . PHP_EOL .
    '    <name>'.Yii::$app->name.'</name>' . PHP_EOL .
    '    <company>'.preg_replace('/"/', '&quot;', Yii::$app->params['companyName']).'</company>' . PHP_EOL .
    '    <url>'.Yii::$app->request->hostInfo.'</url>' . PHP_EOL .
    '        <currencies>' .PHP_EOL .
    '            <currency id="RUR" rate="1"/>' . PHP_EOL .
    '            <currency id="USD" rate="CBRF" plus="3"/>' . PHP_EOL .
    '            <currency id="EUR" rate="CBRF" plus="3"/>' . PHP_EOL .
    '        </currencies>' . PHP_EOL .
    '    </shop>' . PHP_EOL .
    '</yml_catalog>';
?>

