<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\items\models\TypeForm $type
 */
use kartik\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use backend\widgets\map\SelectLink;

$linkClose = ['list'];
?>

<div>
    <?php $form=ActiveForm::begin()?>

    <?=$form->field($type, 'name')?>


    <?= Html::a('Отмена', $linkClose, ['class' => 'btn btn-default btn-sm btn-flat'])?>
    <?= Html::submitButton(($type->id ? 'Изменить' : 'Добавить'), [
        'class' => 'btn btn-primary btn-flat btn-sm',
        'name' => 'signup-button',
    ])?>
    <?php if ($type->id) {
        Modal::begin([
            'header' => $type->name,
            'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
            'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                Html::a('Удалить', ['item-del', 'items_id' => $type->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
        ]);
        echo '<p>Действительно удалить элемент?</p>';
        Modal::end();
    }?>

    <?php ActiveForm::end()?>
</div>
