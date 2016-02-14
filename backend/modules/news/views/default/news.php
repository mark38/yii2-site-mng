<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;

/** @var $this \yii\web\View */
/** @var $news_list \common\models\news\News */
/** @var $news_type \common\models\news\NewsTypes */
/** @var $news_types \common\models\news\NewsTypes */
/** @var $news \common\models\news\News */
/** @var $link \common\models\main\Links */
/** @var $prev_news \common\models\main\Contents */
/** @var $full_news \common\models\main\Contents */

$this->title = 'Новостной блок';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-sm-5">

        <div class="box box-default">
            <div class="box-header with-border">
                <?php if (count($news_types) > 1) {
                    $action = '<i class="fa fa-plus"></i> Добавить новость';
                    $items = array();
                    foreach ($news_types as $type) {
                        $items[] = [
                            'label' => $type->name,
                            'url' => ['', 'news' => 'add', 'news_types_id' => $type->id],
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
                    echo Html::a($action, ['', 'news' => 'add', 'news_types_id' => 1]);
                }?>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">
                <?php if ($news_list) {
                    $i = 0;
                    /** @var $new \common\models\news\News */
                    foreach ($news_list as $new) {
                        $new_date = $new->date !== null ? date('d.m.Y', strtotime($new->date)) : null;
                        $image = $new->link->gallery_images_id ? $new->link->galleryImage->small : false;
                        $ch_link = ['', 'news' => 'ch', 'news_types_id' => $new->news_types_id, 'news_id' => $new->id];

                        echo '<div class="row">' .
                            '<div class="col-sm-11">';


                            echo Html::beginTag('div', ['class' => 'media']);

                                echo Html::beginTag('div', ['class' => 'media-left media-middle']);
                                echo Html::a(Html::img($image, ['class' => 'media-object media-object', 'style' => 'max-height: 100px;']), $ch_link);
                                echo Html::endTag('div');

                                echo Html::beginTag('div', ['class' => 'media-body']);
                                echo Html::tag('h4', '<span class="text-mutted">'.$new_date.'</span> '.$new->link->anchor.' '.Html::a('<i class="fa fa-external-link"></i>', $new->link->url, ['target' => '_blank']));
                                echo Html::tag('div', $new->link->contents[1]->text);
                                echo Html::tag('div', '<small>('.$new->newsType->name.')</small>', ['class' => 'text-muted']);
                                echo Html::endTag('div');

                            echo Html::endTag('div');

                        echo '</div><div class="col-sm-1 text-right">';

                            echo Html::a('<i class="fa fa-pencil-square-o"></i>', $ch_link);

                        echo '</div></div>';

                        if ($i < count($news_list) - 1) echo '<hr>';

                        $i += 1;
                    }
                }?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>

    <?php if (Yii::$app->request->get('news')) {?>
        <div class="col-sm-7">
            <?= $this->render('newsForm', [
                'news_type' => $news_type,
                'news' => $news,
                'link' => $link,
            ])?>
        </div>
    <?php }?>

</div>
