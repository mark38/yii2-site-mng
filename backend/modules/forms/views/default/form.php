<?php

use yii\grid\GridView;

/**
 * @var $formType \common\models\forms\FormTypes
 * @var $formFields \common\models\forms\FormFields
 * @var $forms \common\models\forms\Forms
 */

$this->title = 'Форма - "'.$formType->name.'"';
$this->params['breadcrumbs'][] = ['label' => 'Формы', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;

$columns = [];
foreach ($formFields as $field) {
    $fieldName = $field->name;
    switch ($fieldName) {
        case 'created_at':
            $columns[] = [
                'attribute' => $field->name,
                'label' => $field->label,
                'value' => function($data) use ($fieldName) {
                    return $data->$fieldName ? (new DateTime())->setTimestamp($data->$fieldName)->format('d.m.Y') : null;
                }
            ];
            break;
        case 'form_select1_id':
            $columns[] = [
                'attribute' => $field->name,
                'label' => $field->label,
                'value' => function($data) use ($fieldName) {
                    return $data->$fieldName ? $data->formSelect1->name : null;
                }
            ];
            break;
        default:
            $columns[] = [
                'attribute' => $field->name,
                'label' => $field->label
            ];
            break;
    }
}

?>

<div class="box box-primary">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $forms,
            'columns' => $columns,
        ]) ?>
    </div>
</div>

