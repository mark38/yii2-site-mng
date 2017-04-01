<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Nav;

/**
 * @var $this \yii\web\View
 * @var $items \common\models\items\Items
 * @var $itemTypes \common\models\items\ItemTypes
 */

$this->title = 'Дополнительные элементы контента';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">

    <div class="col-sm-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php
                if ($itemTypes) {
                    $action = '<i class="fa fa-plus"></i> Добавить элемент';
                    if (count($itemTypes) > 1) {
                        $itemList = array();
                        foreach ($itemTypes as $itemType) {
                            $itemList[] = [
                                'label' => $itemType->name,
                                'url' => ['mng', 'item_types_id' => $itemType->id],
                            ];
                        }
                        echo ButtonDropdown::widget([
                            'label' => $action,
                            'encodeLabel' => false,
                            'dropdown' => ['items' => $itemList],
                            'options' => [
                                'class' => 'btn btn-sm btn-default btn-flat'
                            ]
                        ]);
                    } else {
                        echo Html::a($action, ['mng', 'item_types_id' => $itemTypes[0]->id], ['class' => 'btn btn-sm btn-default btn-flat']);
                    }
                } else {
                    echo 'Предварительно '.Html::a('создайте', '/items/types').' типы элементов';
                }
                ?>
            </div>
            <div class="box-body">
                <?php
                if ($itemTypes) {
                    $itemList = array();
                    $amount = 0;
                    foreach ($itemTypes as $itemType) {
                        $amount += count($itemType->items);
                        $itemList[] = [
                            'url' => ['index', 'item_types_id' => $itemType->id],
                            'label' => $itemType->name . Html::tag('span', count($itemType->items), ['class' => 'badge pull-right']),
                            'active' => Yii::$app->request->get('item_types_id') && Yii::$app->request->get('item_types_id') == $itemType->id ? true : false
                        ];
                    }

                    echo Nav::widget([
                        'items' => array_merge(array([
                            'url' => ['index'],
                            'label' => 'Все элементы' . Html::tag('span', $amount, ['class' => 'badge pull-right']),
                            'active' => !Yii::$app->request->get('item_types_id') ? true : false
                        ]), $itemList),
                        'encodeLabels' => false,
                        'options' => ['class' => 'nav nav-pills nav-stacked']
                    ]);
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div class="box box-default">
            <div class="box-body">
                <table class="table table-hover table-condensed">
                    <thead><tr><th>#</th><th>Изображение</th><th>Ссылка</th><th>Наименование</th><th>Заголовок</th><th>Описание</th><th>Цена</th><th>Старая цена</th><th>Статус</th><th class="text-right">Действие</th></tr></thead>
                    <tbody>
                    <?php if ($items) {
                        foreach ($items as $i => $item) {
                            echo $this->render('item', ['item' => $item, 'num' => ($i + 1)]);
                        }
                    } else {
                        echo Html::tag('tr', Html::tag('td', '<em>По заданным параметрам записей не найдено.</em>', ['class' => 'text-muted text-center', 'colspan' => 10]));
                    }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
