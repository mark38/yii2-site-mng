<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use common\models\realty\RealtyPropertyValues;

/** @var $this \yii\web\View */
/** @var $property_value \common\models\realty\RealtyPropertyValues */

?>

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

<div class="form-group">
    <div class="col-sm-5 col-sm-offset-4"><strong>Возможные значения</strong></div>
</div>

<?=$form->field($property_value, 'name')?>

<div class="form-group">
    <div class="col-sm-5 col-sm-offset-4">
        <?php if (Yii::$app->request->get('realty_property_values_id')) {
            echo Html::a('Отмена', Url::current(['realty_property_values_id' => null]), ['class' => 'btn btn-default btn-flat btn-sm']);
        }?>

        <?= Html::submitButton(Yii::$app->request->get('realty_property_values_id') ? 'Изменить' : 'Добавить', [
            'class' => 'btn btn-primary btn-flat btn-sm',
        ])?>

        <?php if (Yii::$app->request->get('realty_property_values_id')) {
            Modal::begin([
                'header' => $property_value->name,
                'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                    Html::a('Удалить', (['delete-property-value', 'id' => Yii::$app->request->get('id'), 'realty_property_values_id' => $property_value->id]), ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Действительно удалить значение свойства?</p>';
            Modal::end();
        }?>
    </div>
</div>

<?php ActiveForm::end()?>

<?php
$property_values = RealtyPropertyValues::find()->all();
if ($property_values) {
    echo Html::beginTag('ul', ['class' => 'list-inline']);
    foreach (RealtyPropertyValues::find()->all() as $value) {
        echo '<li>'.Html::a($value->name, Url::current(['realty_property_values_id' => $value->id])).'</li>';
    }
    echo Html::endTag('ul');
}
?>
