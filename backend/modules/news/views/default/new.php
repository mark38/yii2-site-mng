<?php
use yii\bootstrap\Html;

/**
 * @var $new \common\models\news\News
 * @var $num
 */

$image = $new->link->gallery_images_id ? $new->link->galleryImage->small : false;
$chLink = ['mng', 'news_types_id' => $new->news_types_id, 'id' => $new->id];
$date = $new->date !== null ? date('d.m.Y', strtotime($new->date)) : null;
$period = $new->date_from && $new->date_to ? date('d.m.Y', strtotime($new->date_from)) . ' &mdash; ' . date('d.m.Y', strtotime($new->date_to)) : Html::tag('small', '<em>Не указано</em>', ['class' => 'text-muted']);
$state = $new->link->state ? Html::tag('small', 'Активна', ['class' => 'text-success']) : Html::tag('small', 'Заблокирована', ['class' => 'text-danger']);
?>

<tr>
    <td><?=$num?></td>
    <td><?=Html::a(Html::img($image, ['class' => 'media-object media-object', 'style' => 'max-height: 100px;']), $chLink)?></td>
    <td><?=Html::tag('strong', $date)?></td>
    <td><?=$period?></td>
    <td><?=$new->link->anchor?></td>
    <td><?=Html::tag('small', $new->link->contents[1]->text)?></td>
    <td><?=$state?></td>
    <td class="text-right">
        <ul class="list-inline">
            <li><?=Html::a('<i class="fa fa-pencil-square-o"></i>', $chLink)?></li>
            <li><?=Html::a('<i class="fa fa-external-link"></i>', $new->link->url, ['target' => '_blank'])?></li>
        </ul>
    </td>
</tr>



