<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;

/**
 * @var $this \yii\base\View
 * @var $auctionmbLot \common\models\auctionmb\AuctionmbLots
 * @var $num
 */

$image = $auctionmbLot->link->gallery_images_id ? $auctionmbLot->link->galleryImage->small : false;
$chLink = ['type-mng', 'id' => $auctionmbLot->id];
$state = $auctionmbLot->link->state ? Html::tag('small', 'Активна', ['class' => 'text-success']) : Html::tag('small', 'Заблокирована', ['class' => 'text-danger']);

$lotActiveDate = '';
if ($auctionmbLot->auctionmbActive) {
    $lotActiveDate = date('d.m.Y H:i', strtolower($auctionmbLot->auctionmbActive->created_at));
}
?>

<tr>
    <td><?=$num?></td>
    <td><?=Html::img($image, ['class' => 'media-object media-object', 'style' => 'max-height: 100px;'])?></td>
    <td><?=$auctionmbLot->link->anchor?></td>
    <td><?=$auctionmbLot->seconds?></td>
    <td><?=$auctionmbLot->bets?></td>
    <td><?=$lotActiveDate ? $lotActiveDate : Html::tag('small', 'Не запущен', ['class' => 'text-muted'])?></td>
    <td class="text-right">
        <?php
        echo ButtonDropdown::widget([
            'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
            'dropdown' => [
                'items' => [
                    ['label' => 'Параметры', 'url' => ['lot-mng', 'id' => $auctionmbLot->id]],
                    (!$lotActiveDate ? ['label' => 'Запустить аукцион', 'url' => ['auction-activate', 'auctionmbLotsId' => $auctionmbLot->id]] : '')
                ],
                'options' => ['class' => 'dropdown-menu-right']
            ],
            'encodeLabel' => false,
            'options' => [
                'class' => 'btn btn-xs btn-link clear-caret',
            ],
        ]);
        ?>
    </td>
</tr>
