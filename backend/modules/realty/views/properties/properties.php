<?php
use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $property \common\models\realty\RealtyProperties */
/** @var $properties \common\models\realty\RealtyProperties */
/** @var $property_value \common\models\realty\RealtyPropertyValues */

$this->title = 'Справочник возможных свойст';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-sm-5">

        <div class="box box-default">
            <div class="box-header with-border">
                <?= Html::a('Добавить элемент', ['', 'action' => 'add'], ['class' => 'btn btn-sm btn-default btn-flat']);?>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">
                <?php if ($properties) {
                    echo Html::beginTag('table', ['class' => 'table']);
                    /** @var $realty_property \common\models\realty\RealtyProperties */
                    foreach ($properties as $realty_property) {
                        echo '<tr>' .
                                '<td><strong>'.$realty_property->name.'</strong><br><small clas="text-muted">'.$realty_property->realtyPropertyType->name.' &mdash; '.$realty_property->realtyPropertyType->comment.'</small></td>' .
                                '<td>'.Html::a('<i class="fa fa-pencil-square-o"></i>', ['index', 'action' => 'ch', 'id' => $realty_property->id], ['class' => 'btn btn-default btn-xs btn-falt']).'</td>' .
                             '</tr>';
                    }
                    echo Html::endTag('table');
                }?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>

    <?php if (Yii::$app->request->get('action')) {?>
        <div class="col-sm-7">
            <?= $this->render('propertyForm', [
                'property' => $property,
                'property_value' => $property_value
            ])?>
        </div>
    <?php }?>

</div>
