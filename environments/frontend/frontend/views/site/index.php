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


/** @var \common\models\main\Contents $content */
foreach ($contents as $content) {
    echo $content->text;
} ?>
