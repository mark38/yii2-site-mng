<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;

/** @var $gallery_types \common\models\gallery\GalleryTypes */

$this->title = 'Галереи изображений на сайте';
?>

<div class="row">

    <div class="col-sm-5">

        <div class="box box-default">
            <div class="box-header with-border">
                <?php
                $action = '<i class="fa fa-plus"></i> Добавить фотогалерею';
                if (count($gallery_types) > 1) {
                    $items = array();
                    foreach ($gallery_types as $type) {
                        $items[] = [
                            'label' => $type->comment,
                            'url' => ['', 'gallery_group' => 'add', 'gallery_types_id' => $type->id],
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
                    echo Html::a($action, ['', 'gallery_group' => 'add', 'gallery_types_id' => 1], ['class' => 'btn btn-sm btn-default btn-flat']);
                }?>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">

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
