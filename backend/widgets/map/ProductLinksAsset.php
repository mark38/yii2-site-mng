<?php
namespace backend\widgets\map;

use yii\web\AssetBundle;

class ProductLinksAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/widgets/map/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'map.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'product-links.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}