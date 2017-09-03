<?php
use yii\bootstrap\Html;

/**
 * @var $item \common\models\items\Items
 * @var $num
 */

$image = $item->gallery_images_id ? $item->galleryImage->small : false;
$chLink = ['mng', 'item_types_id' => $item->item_types_id, 'id' => $item->id];
$state = $item->state ? Html::tag('small', 'Активна', ['class' => 'text-success']) : Html::tag('small', 'Заблокирована', ['class' => 'text-danger']);
?>

<tr>
    <td><?=$num?>/<?= $item->id ?></td>
    <td><?=Html::a(Html::img($image, ['class' => 'media-object media-object', 'style' => 'max-height: 100px;']), $chLink)?></td>
    <td><?=$item->url?></td>
    <td><?=$item->name?></td>
    <td><?=$item->title?></td>
    <td><?=Html::tag('small', $item->content ? $item->content->text : null)?></td>
    <td><?=$item->price?></td>
    <td><?=$item->old_price?></td>
    <td><?=$state?></td>
    <td class="text-right">
        <ul class="list-inline">
            <li><?=Html::a('<i class="fa fa-pencil-square-o"></i>', $chLink)?></li>
        </ul>
    </td>
</tr>



