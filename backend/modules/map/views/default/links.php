<?php
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;
use backend\widgets\map\Links;
use backend\modules\map\MapAsset;

/** @var $this \yii\web\View */
/** @var $category \common\models\main\Categories */
/** @var $link \common\models\main\Links */

$this->title = 'Управление ссылками';
$this->params['breadcrumbs'][] = $this->title;
MapAsset::register($this);
?>

<div class="row">
    <div class="col-sm-5">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?=$category->comment?>
                    <?=ButtonDropdown::widget([
                        'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
                        'dropdown' => [
                            'items' => [
                                ['label' => 'Добавить ссылку в корень', 'url' => Url::current(['action' => 'add', 'id' => null])],
                            ],
                        ],
                        'encodeLabel' => false,
                        'options' => [
                            'class' => 'btn btn-link btn-xs root-action',
                        ]
                    ])?>
                </h3>

                <div class="box-tools pull-right">
                    
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>

                </div>
            </div>
            <div class="box-body">
                <?=Links::widget([
                    'categories_id' => Yii::$app->request->get('categories_id'),
                    'parent' => null,
                    'linksId' => Yii::$app->request->get('id') ? Yii::$app->request->get('id') : null
                ])?>
            </div>
        </div>

    </div>

    <div class="col-sm-7"><?= Yii::$app->request->get('action') ? $this->render('linkForm', ['link' => $link]) : ''?></div>
</div>
