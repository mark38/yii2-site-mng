<?php

namespace app\modules\certificate;

use Yii;
use yii\web\AssetBundle;

class CertificateAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/broadcast/assets';
    public $css = [
        //'certificate.css',
    ];
    public $js = [
        //'certificate.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}