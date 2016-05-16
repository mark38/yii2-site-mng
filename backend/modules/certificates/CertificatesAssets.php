<?php

namespace app\modules\certificates;

use Yii;
use yii\web\AssetBundle;

class CertificatesAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/certificates/assets';
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