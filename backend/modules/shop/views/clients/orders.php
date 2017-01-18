<?php
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $shopClientCarts \common\models\shop\ShopClientCarts
 */

$this->title = 'Заказы';

?>

<div class="row">
    <div class="col-md-12">

        <div class="box box-default">
            <div class="box-body">
                <table class="table table-hover table-condensed table-bordered">
                    <thead>
                    <tr><th rowspan="2">#</th><th rowspan="2">Дата заказа</th><th colspan="4">Адрес доставки</th><th colspan="4">Контактная информация</th><th colspan="2">Заказ</th></tr>
                    <tr><th>Город</th><th>Улица</th><th>Дом</th><th>Квартира</th><th>Имя</th><th>Телефон</th><th>Email</th><th>Комментарий</th><th>Кол-во</th><th>Сумма, руб.</th></tr>
                    </thead>
                    <tbody>
                    <?php if ($shopClientCarts) {
                        foreach ($shopClientCarts as $i => $shopClientCart) {
                            echo $this->render('orderPreview', ['num' => ($i + 1), 'shopClientCart' => $shopClientCart]);
                        }
                    } else {
                        echo Html::tag('tr', Html::tag('td', '<em>Для выбранных параметров заказов нет</em>', ['colspan' => 3, 'class' => 'text-center text-muted']));
                    }?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
