<?php
use backend\widgets\map\ProductLinks;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $action
 * @var $type
 * @var $catalogLink \common\models\main\Links
 * @var $link \backend\modules\shop\models\LinkGroupForm
 * @var $group \common\models\shop\ShopGroups
 * @var $galleryGroup \common\models\gallery\GalleryGroups
 * @var $galleryImage \common\models\gallery\GalleryImagesForm
 */

$this->title = 'Управление списком товара и их группами';
?>

<div class="row">
    <div class="col-md-5">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3>
                    <?=$catalogLink ? $catalogLink->anchor : 'Корень'?> <small>(<?=$catalogLink ? $catalogLink->url : '/'?>)</small>
                    <?=ButtonDropdown::widget([
                        'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
                        'dropdown' => [
                            'items' => [
                                ['label' => 'Добавить в корень каталога:'],
                                ['label' => 'Новую группу', 'url' => Url::to(['', 'action' => 'add', 'parent' => $catalogLink->id ?? '', 'type' => 'group'])],
                                ['label' => 'Новую номенклатуру', 'url' => Url::to(['',     'action' => 'add', 'parent' => $catalogLink->id ?? '', 'type' => 'good'])],
                            ],
                        ],
                        'encodeLabel' => false,
                        'options' => [
                            'class' => 'btn btn-link btn-flat btn-xs root-action',
                        ]
                    ])?>
                </h3>
            </div>
            <div class="box-body">
                <?=ProductLinks::widget([
                    'categoriesId' => Yii::$app->params['shop']['categoriesId'],
                    'parent' => $catalogLink->id ?? '',
                    'linksId' => null
                ])?>
            </div>
        </div>

    </div>
    <div class="col-md-7">
        <?php if ($action) {
            switch ($type) {
                case "group": echo $this->render('groupForm', compact('action', 'link', 'group')); break;
                case "good": echo $this->render('goodForm', compact('action', 'link', 'good', 'galleryGroup', 'galleryImage')); break;
            }
        }?>
    </div>
</div>
