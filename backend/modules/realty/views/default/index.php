<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;

/** @var $this \yii\web\View */
/** @var $realty_good \common\models\realty\RealtyGoods */
/** @var $realty_goods \common\models\realty\RealtyGoods */
/** @var $realty_groups \common\models\realty\RealtyGroups */

$this->title = 'Управление объявлениями';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-sm-5">

        <div class="box box-default">
            <div class="box-header with-border">
                <?php
                $action = '<i class="fa fa-plus"></i> Подать объявление';
                if (count($realty_groups) > 1) {
                    $items = array();
                    foreach ($realty_groups as $realty_group) {
                        $items[] = [
                            'label' => $realty_group->name,
                            'url' => ['', 'action' => 'add', 'realty_groups_id' => $realty_group->id],
                        ];
                    }
                    echo ButtonDropdown::widget([
                        'label' => $action,
                        'encodeLabel' => false,
                        'dropdown' => ['items' => $items],
                        'options' => [
                            'class' => 'btn btn-sm btn-default btn-flat'
                        ]
                    ]);
                } else {
                    echo Html::a($action, ['', 'action' => 'add', 'realty_groups_id' => $realty_groups[0]->id], ['class' => 'btn btn-sm btn-default btn-flat']);
                }?>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">
                <?php if ($realty_goods) {
                    echo Html::beginTag('table', ['class' => 'table table-condensed']);
                    /** @var $realty_property \common\models\realty\RealtyProperties */
                    foreach ($realty_goods as $good) {
                        $image = $good->gallery_groups_id ? Html::img($good->galleryGroup->galleryImage->small, ['width' => 64, 'class' => 'img-rounded']) : '';
                        echo '<tr>' .
                                '<td>'.$image.'</td>' .
                                '<td>'.$good->realtyGroup->name.'<br><small class="text-muted">Цена: '.$good->price.'</small></td>' .
                                '<td>'.Html::a('<i class="fa fa-pencil-square-o"></i>', ['', 'action' => 'ch', 'realty_groups_id' => $good->realty_groups_id, 'id' => $good->id]).'</td>' .
                             '</tr>';
                    }
                    echo Html::endTag('table');
                }?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>

    <?php if (Yii::$app->request->get('action')) {?>
        <div class="col-sm-7">
            <?= $this->render('realtyGoodForm', [
                'realty_good' => $realty_good,
                'realty_groups' => $realty_groups
            ])?>
        </div>
    <?php }?>

</div>
