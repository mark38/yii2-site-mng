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
                <h3 class="box-title" style="margin-right: 10px;"><?=$category->comment?></h3>
                <?=ButtonDropdown::widget([
                    'label' => '<i class="fa fa-cog"></i>',
                    'dropdown' => [
                        'items' => [
                            ['label' => 'Добавить ссылку в корень', 'url' => Url::current(['mng_link' => 'add', 'links_id' => null])],
                        ],
                    ],
                    'encodeLabel' => false,
                    'options' => [
                        'class' => 'btn btn-default btn-xs root-action',
                        
                    ]
                ])?>

                <div class="box-tools pull-right">

                    <input type="text" class="form-control input-sm" placeholder="Поиск...">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>

                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body links-list">
                <?=Links::widget(['categories_id' => Yii::$app->request->get('categories_id')])?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>

    <div class="col-sm-7"><?= Yii::$app->request->get('mng_link') ? $this->render('linkForm', ['link' => $link]) : ''?></div>
</div>
