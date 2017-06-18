<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\items\models\TypeForm $type
 * @var \common\models\items\ItemTypes $types
 * @var string $action
 */
use kartik\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Типы элементов контента';
$this->params['breadcrumbs'][] = ['label' => 'Все элементы', 'url' => Yii::$app->request->baseUrl.'/items/index'];
$this->params['breadcrumbs'][] = $this->title;

$columns[] = [
    'attribute' => 'name',
];
$columns[] = [
    'attribute' => 'links_id',
    'value' => function($data) {
        return $data->links_id ? $data->link->anchor : null;
    }
];
$columns[] = [
    'attribute' => 'gallery_types_id',
    'value' => function($data) {
        return $data->gallery_types_id ? $data->galleryType->comment.' ('.$data->galleryType->small_width.'x'.$data->galleryType->small_height.'; '.$data->galleryType->large_width.'x'.$data->galleryType->large_height.')' : null;
    }
];
$columns[] = [
    'attribute' => 'gallery_groups_id',
    'value' => function($data) {
        return $data->gallery_groups_id ? $data->galleryGroup->name : null;
    }
];
$columns[] = [
    'label' => '',
    'format' => 'html',
    'value' => function($data) {
        return Html::a('<i class="fa fa-pencil" aria-hidden="true"></i>', ['', 'id' => $data->id, 'action' => 'ch']);
    }
]
?>

<div class="row">

    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?= Html::a('Добавить элемент', ['', 'action' => 'add'], ['class' => 'btn btn-sm btn-default btn-flat']);?>
            </div>
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $types,
                    'columns' => $columns,
                ])?>
            </div>
        </div>
    </div>

    <?php if ($action) {?>
        <div class="col-sm-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <?php echo ($action == 'add' ? 'Новый тип контента' : 'Редактирование '.$type->name) ?>
                </div>
                <div class="box-body">
                    <?=$this->render('typeForm', ['type' => $type])?>
                </div>
            </div>
        </div>
    <?php } ?>

</div>