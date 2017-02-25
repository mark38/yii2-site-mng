<?php
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $auctionmbLots \common\models\auctionmb\AuctionmbLots
 */

$this->title = 'Список лотов';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <?=Html::a('<i class="fa fa-plus"></i> Добавить лот', 'lot-mng', ['class' => 'btn btn-default btn-sm btn-flat'])?>
            </div>
            <div class="box-body">
                <table class="table table-hover table-condensed">
                    <thead><tr><th>#</th><th>Изображение</th><th>Наименование лота</th><th>Секунды</th><th>Ставки</th><th>Активный аукцион</th><th class="text-right">Действие</th></tr></thead>
                    <tbody>
                    <?php if ($auctionmbLots) {
                        foreach ($auctionmbLots as $i => $auctionmbLot) {
                            echo $this->render('lot', ['auctionmbLot' => $auctionmbLot, 'num' => ($i + 1)]);
                        }
                    } else {
                        echo Html::tag('tr', Html::tag('td', '<em>По заданным параметрам записей не найдено.</em>', ['class' => 'text-muted text-center', 'colspan' => 6]));
                    }?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-body">
                <ul class="list-unstyled">
                    <li><strong>Секунды</strong> &mdash; количество cекунд выделенных на аукцион.</li>
                    <li><strong>Ставки</strong> &mdash; минимальное количество ставок при котором лот будет разыгран.</li>
                </ul>
            </div>
        </div>

    </div>
</div>
