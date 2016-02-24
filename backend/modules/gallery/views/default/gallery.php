<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;
use common\models\gallery\GalleryTypes;

/** @var $gallery_types \common\models\gallery\GalleryTypes */
/** @var $gallery_group \common\models\gallery\GalleryGroups */
/** @var $gallery_groups \common\models\gallery\GalleryGroups */

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
                            'url' => ['', 'action' => 'add', 'gallery_types_id' => $type->id],
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
                    echo Html::a($action, ['', 'action' => 'add', 'gallery_types_id' => 1], ['class' => 'btn btn-sm btn-default btn-flat']);
                }?>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">
                <?php if ($gallery_groups) {?>
                    <table class="table">
                        <tbody>
                        <?php foreach($gallery_groups as $group) {
                            $image = $group->gallery_images_id ? Html::img($group->galleryImage->small, ['width' => 64, 'class' => 'img-rounded']) : '';
                            echo '<tr>' .
                                    '<td>'.$image.'</td>' .
                                    '<td>'.$group->name.'<br><small class="text-muted">('.$group->galleryType->comment.')</small></td>' .
                                    '<td>'.Html::a('<i class="fa fa-pencil-square-o"></i>', ['', 'action' => 'ch', 'gallery_groups_id' => $group->id]).'</td>' .
                                 '</tr>';
                        }?>
                        </tbody>
                    </table>
                <?php }?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>

    <?php if ($gallery_group) {?>
        <div class="col-sm-7">
            <?php
            print_r($gallery_group);
            ?>
            <?/*= $this->render('galleryForm', [
                'gallery_group' => $gallery_group,
            ])*/?>
        </div>
    <?php }?>

</div>
