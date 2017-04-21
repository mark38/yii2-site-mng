<?php
namespace app\modules\settings;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/settings/assets';
    /**
     * @inheritdoc
     */
    public $css = [

    ];
    /**
     * @inheritdoc
     */
    public $js = [];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}