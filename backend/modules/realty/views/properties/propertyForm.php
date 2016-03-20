<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use common\models\realty\RealtyPropertyTypes;


/** @var $property \common\models\realty\RealtyProperties */
/** @var $property_value \common\models\realty\RealtyPropertyValues */

$link_close = [''];

$property_types = array();
foreach (RealtyPropertyTypes::find()->all() as $property_type) {
    $property_types[$property_type->id] = $property_type->name.' &mdash; '.$property_type->comment;
}

?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?=Yii::$app->request->get('action') == 'add' ? 'Новое свойство' : 'Редактирование свойства ('.$property->name.')'?></h3>
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
                ],
            ],
        ]); ?>

        <?= $form->field($property, 'name')?>
        <?= $form->field($property, 'realty_property_types_id')->dropDownList($property_types, ['encode' => false])?>

        <div class="form-group">
            <div class="col-sm-5 col-sm-offset-4">
                <?= Html::a('Отмена', $link_close, [
                    'class' => 'btn btn-default btn-flat btn-sm',
                ])?>
                <?= Html::submitButton(Yii::$app->request->get('action') == 'add' ? 'Добавить' : 'Изменить', [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                ])?>

                <?php if (Yii::$app->request->get('action') == 'ch') {
                    Modal::begin([
                        'header' => $property->name,
                        'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                        'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                            Html::a('Удалить', ['delete', 'id' => $property->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                    ]);
                    echo '<p>Действительно удалить свойство?</p>';
                    Modal::end();
                }?>
            </div>
        </div>

        <?php ActiveForm::end()?>

    </div>
    <?php if (Yii::$app->request->get('action') == 'ch' && array_search($property->realty_property_types_id, [0,3])) {?>
        <div class="box-footer"><?=$this->render('valueForm', ['property_value' => $property_value])?></div>
    <?php }?>
</div>