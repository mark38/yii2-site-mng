<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/** @var $property \common\models\shop\ShopProperties */

$link_close = [''];
?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Управление свойством</h3>
        <div class="box-tools pull-right">
            <?=Html::a('<i class="fa fa-times"></i>', $link_close, ['class' => 'btn btn-box-tool'])?>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-4',
                    'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-5',
                    'error' => '',
                    'hint' => '',
                ]
            ]
        ])?>

        <?=$form->field($property, 'verification_code')->staticControl()?>
        <?=$form->field($property, 'name')?>
        <?=$form->field($property, 'anchor')?>
        <?=$form->field($property, 'url')?>
        <?=$form->field($property, 'unit')?>
        <?=$form->field($property, 'seq')?>
        <?=$form->field($property, 'range')->checkbox()?>
        <?=$form->field($property, 'state')->checkbox()?>

        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <?= Html::a('Отмена', $link_close, ['class' => 'btn btn-default btn-sm btn-flat'])?>

                <?= Html::submitButton(($property->id ? 'Изменить' : 'Добавить'), [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                    'name' => 'signup-button',
                    'value' => 'Добавить',
                ])?>

                <?php if ($property->id) {
                    echo Html::a('Редакторовать описание', Url::to(['contentMng', 'properties_id' => $property->id]), [
                            'class' => 'btn btn-info btn-flat btn-sm'
                        ]).'&nbsp;';
                    Modal::begin([
                        'header' => $property->name,
                        'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                        'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                                    Html::a('Удалить', ['property-del', 'properties_id' => $property->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                    ]);
                    echo '<p>Свойство будет удалено со всем содержимым, в том числе значениями свойств.</p><p>Действительно удалить свойство?</p>';
                    Modal::end();
                }?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </div>
</div>
