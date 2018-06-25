<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\shop\ShopCategories $categories
 * @var string $action
 */
use kartik\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Категории товаров';
$this->params['breadcrumbs'][] = ['label' => 'Все элементы', 'url' => Yii::$app->request->baseUrl.'/items/index'];
$this->params['breadcrumbs'][] = $this->title;


$columns[] = [
    'attribute' => 'id',
];
$columns[] = [
    'attribute' => 'name',
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
                    'dataProvider' => $categories,
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
                    <?=$this->render('categoryForm', ['type' => $type])?>
                </div>
            </div>
        </div>
    <?php } ?>

</div>
