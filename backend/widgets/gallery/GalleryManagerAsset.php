<?php
namespace backend\widgets\gallery;

use yii\web\AssetBundle;

class GalleryManagerAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/gallery/assets';
    public $css = [
        'galleryManager.css',
    ];
    public $js = [
        'galleryManager.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}