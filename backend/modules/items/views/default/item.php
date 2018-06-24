<?php
/**
 * @var $item \common\models\items\Items
 * @var $num
 */

use yii\bootstrap\Html;
use rmrevin\yii\fontawesome\FA;

$image = $item->gallery_images_id ? $item->galleryImage->small : false;
$chLink = ['mng', 'item_types_id' => $item->item_types_id, 'id' => $item->id];
$state = $item->state ? Html::tag('small', 'Активна', ['class' => 'text-success']) : Html::tag('small', 'Заблокирована', ['class' => 'text-danger']);
?>

<tr>
    <td<?= (!$item->state ? ' style="text-decoration: line-through;"; class="text-muted"' : '') ?>><?= $num ?></td>
    <td><?= Html::a(Html::img($image, ['class' => 'media-object media-object', 'style' => 'max-width: 128px; max-height: 64px;']), $chLink) ?></td>
    <td<?= (!$item->state ? ' style="text-decoration: line-through;"; class="text-muted"' : '') ?>><?= $item->name ?></td>
    <td<?= (!$item->state ? ' style="text-decoration: line-through;"; class="text-muted"' : '') ?>><?= $item->title ?></td>
    <td class="text-right">
        <nobr>
            <?php if ($item->url): ?><?= Html::a(FA::i('external-link'), $item->url, ['target' => '_blank']) ?> <?php endif; ?>
            <?= Html::a('<i class="fa fa-pencil-square-o"></i>', $chLink) ?>
        </nobr>
    </td>
</tr>



