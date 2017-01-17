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
            <div class="box-body">
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
                    echo Html::a($action, ['', 'news' => 'add', 'news_types_id' => 1], ['class' => 'btn btn-sm btn-default btn-flat']);
                }?>

                <hr>

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
                    $items[] = [
                        'url' => ['index'],
                        'label' => 'Все нововсти' . Html::tag('span', $amount, ['class' => 'badge pull-right']),
                        'active' => !Yii::$app->request->get('news_types_id') ? true : false
                    ];

                    echo Nav::widget([
                        'items' => $items,
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
                <?php if ($newsList) {
                    $i = 0;
                    /** @var $new \common\models\news\News */
                    foreach ($newsList as $new) {
                        $new_date = $new->date !== null ? date('d.m.Y', strtotime($new->date)) : null;
                        $new->date_range = $new->date_from && $new->date_to ? 'период публикации: '.date('d.m.Y', strtotime($new->date_from)).' - '.date('d.m.Y', strtotime($new->date_to)) : 'опубликовано';
                        $image = $new->link->gallery_images_id ? $new->link->galleryImage->small : false;
                        $ch_link = ['mng', 'news_types_id' => $new->news_types_id, 'id' => $new->id];

                        echo '<div class="row">' .
                            '<div class="col-sm-10">';


                            echo Html::beginTag('div', ['class' => 'media']);

                                echo Html::beginTag('div', ['class' => 'media-left media-middle']);
                                echo Html::a(Html::img($image, ['class' => 'media-object media-object', 'style' => 'max-height: 100px;']), $ch_link);
                                echo Html::endTag('div');

                                echo Html::beginTag('div', ['class' => 'media-body']);
                                echo Html::tag('strong', '<span class="text-info">'.$new_date.'</span> '.$new->link->anchor);
                                echo Html::tag('div', $new->link->contents[1]->text);
                                echo Html::tag('div', '<small>(<strong>'.$new->newsType->name.'</strong> | '.$new->date_range.')</small>', ['class' => 'text-muted']);
                                echo Html::endTag('div');

                            echo Html::endTag('div');

                        echo '</div><div class="col-sm-2 text-right">';

                            echo '<ul class="list-inline">' .
                                    '<li>'.Html::a('<i class="fa fa-pencil-square-o"></i>', $ch_link).'</li>' .
                                    '<li>'.Html::a('<i class="fa fa-external-link"></i>', $new->link->url, ['target' => '_blank']).'</li>' .
                                 '</ul>';

                        echo '</div></div>';

                        if ($i < count($news_list) - 1) echo '<hr>';

                        $i += 1;
                    }
                }?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>

</div>
