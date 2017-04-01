<?php
namespace app\modules\forms;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/forms/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'items.css',
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