<?php
namespace app\modules\news;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/news/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'news.css',
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