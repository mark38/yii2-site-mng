<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\gallery\GalleryGroups $galleryGroup
 * @var integer $i
 */

use kartik\helpers\Html;
$chLink = ['mng', 'gallery_types_id' => $galleryGroup->gallery_types_id, 'gallery_groups_id' => $galleryGroup->id];

?>

<tr>
    <td><?= $i ?></td>
    <td><?= ($galleryGroup->galleryImage ? Html::img($galleryGroup->galleryImage->small, ['style' => 'max-width: 128px; max-height: 64px;']) : '&mdash;') ?></td>
    <td><?= ($galleryGroup->name ? $galleryGroup->name : '&mdash;') ?></td>
    <td><?= $galleryGroup->galleryType->comment . '<br><small class="text-muted">маленькое: '.$galleryGroup->galleryType->small_width.'x'.$galleryGroup->galleryType->small_height.'; большое: '.$galleryGroup->galleryType->large_width.'x'.$galleryGroup->galleryType->large_height.'</small>' ?></td>
    <td><?= Html::a('<i class="fa fa-pencil-square-o"></i>', $chLink) ?></td>
</tr>
