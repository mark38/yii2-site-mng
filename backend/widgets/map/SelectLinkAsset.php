<?php
namespace backend\widgets\map;

use yii\web\AssetBundle;

class SelectLinkAsset extends AssetBundle
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
        'select-link.js',
    ];
    public $php = [
        'links.php',
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