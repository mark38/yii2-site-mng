<?php
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $shopClientCarts \common\models\shop\ShopClientCarts
 */

$this->title = 'Заказы';

?>

<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body"></div>
        </div>
    </div>
    <div class="col-md-9">

        <div class="box box-default">
            <div class="box-body">
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr><th>#</th><th>Дата</th><th>Город</th><th>Имя</th><th>Телефон</th><th>Email</th><th>Кол-во</th><th>Сумма, руб.</th><th class="text-right">Действия</th></tr>
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

<?php
Modal::begin([
    'header' => '',
    'footer' => Html::button('Закрыть', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm btn-close']),
    'options' => [
        'class' => 'modal-preview fade',
        'id' => 'modal-order',
        'data-url' => Url::to(['order']),
    ],
    'size' => 'modal-lg'
]);
Modal::end();
?>
