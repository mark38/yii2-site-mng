<?php
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $shopClientCart \common\models\shop\ShopClientCarts
 * @var $num
 */

$items = $shopClientCart->shopCart->shopCartItems;
$goods = $shopClientCart->shopCart->shopCartGoods;
$amount = 0;
$price = 0;
if ($items) {
    /** @var \common\models\shop\ShopCartItems $item */
    foreach ($items as $item) {
        $amount += $item->amount;
        $price += $item->amount * $item->price;
    }
}
if ($goods) {
    /** @var \common\models\shop\ShopCartGoods $good */
    foreach ($goods as $good) {
        $amount += $good->amount;
        $price += $good->amount * $good->price;
    }
}

?>

<tr>
    <td><?=$num?></td>
    <td><?=date('d.m.Y H:i', $shopClientCart->shopCart->checkout_at)?></td>
    <td><?=$shopClientCart->shopClient->city ? $shopClientCart->shopClient->city : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->fio ? $shopClientCart->shopClient->fio . ($shopClientCart->shopClient->shop_users_id ? ' '.Html::tag('small', '<i class="fa fa-star-o" aria-hidden="true"></i>', ['class' => 'text-success']) : '') : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->phone ? $shopClientCart->shopClient->phone : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->email ? $shopClientCart->shopClient->email : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$amount?></td>
    <td><?=preg_replace('/\,00/', '', number_format($price, 2, ',', '&thinsp;'))?></td>
    <td class="text-right">
        <?=Html::button('<i class="fa fa-eye" aria-hidden="true"></i>', [
            'class' => 'btn btn-xs btn-link',
            'onclick' => 'getOrder('.$shopClientCart->id.')',
        ])?>
    </td>
</tr>
