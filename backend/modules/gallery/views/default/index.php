<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\gallery\GalleryTypes $galleryTypes
 * @var \common\models\gallery\GalleryGroups $galleryGroups
 */

use kartik\helpers\Html;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Nav;

$this->title = 'Управление фотогалереей';

?>

<div class="row">

    <div class="col-lg-5 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php
                $action = '<i class="fa fa-plus"></i> Добавить фотогалерею';
                if (count($galleryTypes) > 1) {
                    $items = array();
                    /** @var \common\models\gallery\GalleryTypes $type */
                    foreach ($galleryTypes as $type) {
                        $items[] = [
                            'label' => $type->comment,
                            'url' => ['mng', 'gallery_types_id' => $type->id],
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
                    echo Html::a($action, ['mng', 'action' => 'add', 'gallery_types_id' => $galleryTypes[0]->id], ['class' => 'btn btn-sm btn-default btn-flat']);
                }
                ?>
            </div>
            <div class="box-body">
                <?php
                if ($galleryTypes) {
                    $items = array();
                    $amount = 0;
                    /** @var \common\models\gallery\GalleryTypes $galleryType */
                    foreach ($galleryTypes as $galleryType) {
                        $amount += count($galleryType->galleryGroups);
                        $items[] = [
                            'url' => ['index', 'gallery_types_id' => $galleryType->id],
                            'label' => $galleryType->comment . ' <small class="text-muted">(маленькое: '.$galleryType->small_width.'x'.$galleryType->small_height.'; большое: '.$galleryType->large_width.'x'.$galleryType->large_height.')</small>' . Html::tag('span', count($galleryType->galleryGroups), ['class' => 'badge pull-right']),
                            'active' => Yii::$app->request->get('gallery_types_id') && Yii::$app->request->get('gallery_types_id') == $galleryType->id ? true : false
                        ];
                    }

                    echo Nav::widget([
                        'items' => array_merge(array([
                            'url' => ['index'],
                            'label' => 'Все элементы' . Html::tag('span', $amount, ['class' => 'badge pull-right']),
                            'active' => !Yii::$app->request->get('gallery_types_id') ? true : false
                        ]), $items),
                        'encodeLabels' => false,
                        'options' => ['class' => 'nav nav-pills nav-stacked']
                    ]);
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-sm-12">
        <div class="box box-default">
            <div class="box-body">
                <table class="table table-hover table-condensed">
                    <thead><tr><th><nobr>#</nobr></th><th>Основное<br />изображение</th><th>Наименование</th><th>Тип<br />Параметры</th><th></th></tr></thead>
                    <tbody>
                    <?php
                    if ($galleryGroups) {
                        foreach ($galleryGroups as $i => $galleryGroup) {
                            echo $this->render('galleryGroupPreview', ['galleryGroup' => $galleryGroup, 'i' => $i + 1]);
                        }
                    } else {
                        echo Html::tag('tr', Html::tag('td', '<em>По заданным параметрам записей не найдено.</em>', ['class' => 'text-muted text-center', 'colspan' => 5]));
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
