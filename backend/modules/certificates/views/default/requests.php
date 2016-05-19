<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\Dropdown;
use yii\helpers\Url;

$this->title = 'Список запросов для задачи от '.date('d.m.Y H:i', $task->created_at);

/**
 * @var $requests \common\models\certificates\Requests;
 * @var $request \common\models\certificates\Requests;
 * @var $companies \common\models\main\Companies;
 * @var $new boolean;
 */
?>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><a href="<?= Url::to(['/certificates/requests', 'tasks_id' => $task->id, 'new' => true]) ?>" class="btn btn-default btn-sm btn-flat <?= $task->state ? '' : 'disabled' ?>"><i class="fa fa-plus"></i> Добавить запрос</a></h3>
            </div>
            <div class="box-body">
                <?php
                $columns = [
                    'company.name',
                    [
                        'label' => '',
                        'format' => 'raw',
                        'contentOptions' =>['class' => 'menu-col skip-export'],
                        'value' => function ($data) use ($task) {
                            $items[] = ['label' => 'Редактировать', 'url' => ['/certificates/requests', 'tasks_id' => $task->id, 'id' => $data->id]];
                            $items[] = ['label' =>'Удалить', 'url' => ['#'], 'linkOptions' =>
                                [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#myModal',
                                    'onclick' => '$("#del-certificate").attr("href", "del-certificate?id='.$data->id.'");',
                                ]
                            ];

                            Modal::begin([
                                'header' => '<h2>Действительно удалить справку из списка?</h2>',
                                'id' => 'myModal'
                            ]);
                            echo '<div class="panel">Также будут удалены все задачи, связанные с данной справкой.</div>';
                            echo '<div class="text-center"><ul class="list-inline">' .
                                '<li>'.Html::a('<span class="fa fa-times"></span> Удалить', '', ['id' => 'del-certificate', 'class' => 'btn btn-sm btn-flat btn-danger']).'</li>' .
                                '<li>'.Html::a('Отменить', '', ['class' => 'btn btn-sm btn-flat btn-default ', 'data-dismiss' => "modal", 'aria-hidden' => true]).'</li>' .
                                '</ul></div>';
                            Modal::end();

                            return '<div class="dropdown"><span class="btn btn-flat menu-button dropdown-toggle '.($task->state ? '' : 'disabled').'" data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i></span>' .
                            Dropdown::widget([
                                'items' => $items,
                                'options' => [
                                    'class' => 'wagon-control-ul'
                                ]
                            ]).
                            '</div>';
                        },
                    ],
                ];

                echo GridView::widget([
                    'dataProvider' => $requests,
                    'columns' => $columns,
                    'layout' => '<div class="pull-left">{summary}</div><div class="pull-right">{export}</div>{items}{pager}',

                    'toolbar' => [
                        '{export}'

                    ],
                    'export' => [
                        'options' => ['class' => 'btn-default btn-xs btn-flat'],
                    ],

                    'bordered' => true,
                    'striped' => false,
                    'condensed' => true,
                    'responsive' => false,
                    'hover' => true,

                    'exportConfig' => [
                        GridView::TEXT => [
                            'config' => [
                                'colDelimiter' => " | ",
                                'rowDelimiter' => "\r\n",
                            ]
                        ],
                        GridView::PDF => ['config' => [
                            'mode' => 'utf-8'
                        ]],
                        GridView::EXCEL => [],
                    ]
                ]);
                ?>
            </div>
        </div>

        <?php if ($task->state) { ?>
            <div class="box box-default">
                <div class="box-body">
                    <?= Html::a('Закрыть задачу и сформировать файлы excel', ['/certificates/close-task', 'tasks_id' => $task->id], ['class' => 'btn btn-success btn-sm btn-flat']) ?>
                </div>
            </div>
        <? } ?>

        <?php if (!$task->state && $file_paths) { ?>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Запрашиваемые справки в формате excel</h3>
                    <div class="box-tools pull-right">
                        <?= $excel_zip ? Html::a('<i class="fa fa-file-archive-o" aria-hidden="true"></i> Скачать все', ['/'.$excel_zip], ['class' => 'btn btn-default btn-xs btn-flat']) : '' ?>
                    </div>
                </div>
                <div class="box-body">
                    <ul class="list-unstyled">
                        <?php
                        foreach ($file_paths as $path) {
                            echo '<li>'.Html::a($path->name, ['/'.$path->path]).'</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Разбиение на архивы по компаниям</h3>
                </div>
                <div class="box-body">
                    <ul class="list-unstyled">
<?=
echo '<label class="control-label">Upload Document</label>';
echo File::widget([
    'name' => 'attachment_3',
]);
?>
                    </ul>
                </div>
            </div>
        <? } ?>



    </div>
    <div class="col-sm-6">
        <?php
        if ($request) {
            echo $this->render('mng-request', [
                'request' => $request,
                'companies' => $companies,
                'requested_certificates' => $requested_certificates,
                'new' => $new ? true : false
            ]);
        }
        ?>
    </div>
</div>