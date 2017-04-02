<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Nav;


$this->title = 'Все пользователи';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">

    <div class="col-sm-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php
                if ($formTypes) {
                    $action = '<i class="fa fa-plus"></i> Добавить элемент';
                    if (count($formTypes) > 1) {
                        $formList = array();
                        foreach ($formTypes as $formType) {
                            $formList[] = [
                                'label' => $formType->name,
                                'url' => ['mng', 'form_types_id' => $formType->id],
                            ];
                        }
                        echo ButtonDropdown::widget([
                            'label' => $action,
                            'encodeLabel' => false,
                            'dropdown' => ['items' => $formList],
                            'options' => [
                                'class' => 'btn btn-sm btn-default btn-flat'
                            ]
                        ]);
                    } else {
                        echo Html::a($action, ['mng', 'form_types_id' => $formTypes[0]->id], ['class' => 'btn btn-sm btn-default btn-flat']);
                    }
                } else {
                    echo 'Предварительно '.Html::a('создайте', '/forms/types').' типы элементов';
                }
                ?>
            </div>
            <div class="box-body">
                <?php
                if ($formTypes) {
                    $formList = array();
                    $amount = 0;
                    foreach ($formTypes as $formType) {
                        $amount += count($formType->forms);
                        $formList[] = [
                            'url' => ['index', 'form_types_id' => $formType->id],
                            'label' => $formType->name . Html::tag('span', count($formType->forms), ['class' => 'badge pull-right']),
                            'active' => Yii::$app->request->get('form_types_id') && Yii::$app->request->get('form_types_id') == $formType->id ? true : false
                        ];
                    }

                    echo Nav::widget([
                        'items' => array_merge(array([
                            'url' => ['index'],
                            'label' => 'Все элементы' . Html::tag('span', $amount, ['class' => 'badge pull-right']),
                            'active' => !Yii::$app->request->get('form_types_id') ? true : false
                        ]), $formList),
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
                    <thead><tr><?= $columns ?></tr></thead>
                    <tbody>
                    <?php if ($forms) {
                        foreach ($forms as $i => $form) {
                            echo $this->render('form', compact('form', 'fields'));
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
