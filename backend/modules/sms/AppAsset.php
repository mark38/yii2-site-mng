<?php

namespace app\modules\sms;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/sms/assets';
    public $css = [
//        'sms.css'
    ];
    public $js = [
        'sms.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy'=>true,
    ];
}