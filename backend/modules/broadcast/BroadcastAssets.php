<?php

namespace app\modules\broadcast;

use Yii;
use yii\web\AssetBundle;

class BroadcastAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/broadcast/assets';
    public $css = [
        'broadcast.css',
    ];
    public $js = [
        'broadcast.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}