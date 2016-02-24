<?php
namespace backend\modules\map;

use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/map/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/map.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/map.js',
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