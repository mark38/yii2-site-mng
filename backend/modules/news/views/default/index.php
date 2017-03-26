<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;
use app\modules\news\AppAsset;
use yii\bootstrap\Nav;

/**
 * @var $this \yii\web\View
 * @var $newsList \common\models\news\News
 * @var $newsTypes \common\models\news\NewsTypes
 */

AppAsset::register($this);

$this->title = 'Новостной блок';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php
                $action = '<i class="fa fa-plus"></i> Добавить новость';
                if (count($newsTypes) > 1) {
                    $items = array();
                    foreach ($newsTypes as $newsType) {
                        $items[] = [
                            'label' => $newsType->name,
                            'url' => ['mng', 'news_types_id' => $newsType->id],
                        ];
                    }
                    echo ButtonDropdown::widget([
                        'label' => $action,
                        'encodeLabel' => false,
                        'dropdown' => ['items' => $items],
                        'options' => [
                            'class' => 'btn btn-sm btn-default btn-flat'
                        ]
                    ]);
                } else {
                    echo Html::a($action, ['mng', 'news_types_id' => 1], ['class' => 'btn btn-sm btn-default btn-flat']);
                }?>
            </div>
            <div class="box-body">
                <?php if ($newsTypes) {
                    $items = [];
                    $amount = 0;
                    foreach ($newsTypes as $newsType) {
                        $amount += count($newsType->news);
                        $items[] = [
                            'url' => ['index', 'news_types_id' => $newsType->id],
                            'label' => $newsType->name . Html::tag('span', count($newsType->news), ['class' => 'badge pull-right']),
                            'active' => Yii::$app->request->get('news_types_id') && Yii::$app->request->get('news_types_id') == $newsType->id ? true : false
                        ];
                    }

                    echo Nav::widget([
                        'items' => array_merge(array([
                            'url' => ['index'],
                            'label' => 'Все нововсти' . Html::tag('span', $amount, ['class' => 'badge pull-right']),
                            'active' => !Yii::$app->request->get('news_types_id') ? true : false
                        ]), $items),
                        'encodeLabels' => false,
                        'options' => ['class' => 'nav nav-pills nav-stacked']
                    ]);
                }?>
            </div>
        </div>
    </div>

    <div class="col-md-9">

        <div class="box box-default">
            <div class="box-header with-border">

                <h3>Список нововстей</h3>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>

            </div>

            <div class="box-body">
                <table class="table table-hover table-condensed">
                    <thead><tr><th>#</th><th>Изображение</th><th>Дата</th><th>Период</th><th>Заголовок</th><th>Предварительное описание</th><th>Статус</th><th class="text-right">Действие</th></tr></thead>
                    <tbody>
                    <?php if ($newsList) {
                        /** @var \common\models\news\News $new */
                        foreach ($newsList as $i => $new) {
                            echo $this->render('new', ['new' => $new, 'num' => ($i + 1)]);
                        }
                    } else {
                        echo Html::tag('tr', Html::tag('td', '<em>По заданным параметрам записей не найдено.</em>', ['class' => 'text-muted text-center', 'colspan' => 8]));
                    }?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
