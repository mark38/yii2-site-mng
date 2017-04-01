<?php
use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use backend\widgets\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;

$this->title = 'Редактирование элемента';
$this->params['breadcrumbs'][] = ['label' => 'Все элементы', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => $formType->name, 'url' => ['index', 'form_types_id' => $formType->id]];

$linkClose = ['index', 'form_types_id' => $formType->id];

?>

<div class="row">
    <div class="col-sm-8">

        <div class="box box-default">
            <div class="box-body">


            </div>
        </div>

    </div>
</div>
