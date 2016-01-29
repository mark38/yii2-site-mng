<?php
namespace backend\models\shop;

use common\models\sh\ShGoods;
use common\models\sh\ShItems;
use common\models\sh\ShPriceGood;
use common\models\sh\ShPriceItem;
use common\models\sh\ShPriceTypes;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Offers extends Model
{
    private $offers_file;

    public function parser($offers_file)
    {
        $this->offers_file;
        $sxe = simplexml_load_file($offers_file);
        $price_types_sxe = $sxe->xpath('/КоммерческаяИнформация/ПакетПредложений/ТипыЦен/ТипЦены');
        $this->parserPriceTypes($price_types_sxe);
        $offers_sxe = $sxe->xpath('/КоммерческаяИнформация/ПакетПредложений/Предложения/Предложение');
        $this->parserOffers($offers_sxe);
    }

    private function parserPriceTypes($price_types_sxe)
    {
        foreach ($price_types_sxe as $item) {
            $price_type = ShPriceTypes::findOne(['code' => $item->{'Ид'}]);
            if (!$price_type) {
                $price_type = new ShPriceTypes();
                $price_type->code = strval($item->{'Ид'});
            }
            $price_type->type = strval($item->{'Наименование'});
            $price_type->save();
        }

        return true;
    }

    private function parserOffers($offers_sxe)
    {
        /* Поиск и удаление цен для товара и его характеристик */
        foreach ($offers_sxe as $item_sxe) {
            if (preg_match('/(.+)#(.+)/', $item_sxe->{'Ид'}, $matches)) {
                $good_code = strval($matches[1]);
                $item_code = strval($matches[2]);
                $prices_item = ShPriceItem::find()->innerJoinWith('item')->where(['mod_sh_items.code' => $item_code])->all();
                if ($prices_item) ShPriceItem::deleteAll(['id' => ArrayHelper::getColumn($prices_item, 'id')]);
            } else {
                $good_code = strval($item_sxe->{'Ид'});
            }
            $price_good = ShPriceGood::find()->innerJoinWith('good')->where(['mod_sh_goods.code' => $good_code])->all();
            if ($price_good) ShPriceGood::deleteAll(['id' => ArrayHelper::getColumn($price_good, 'id')]);
        }

        $price_types = ArrayHelper::map(ShPriceTypes::find()->all(), 'code', 'id');
        $good_min_price = array();
        foreach ($offers_sxe as $item_sxe) {
            $item_code = false;
            if (preg_match('/(.+)#(.+)/', $item_sxe->{'Ид'}, $matches)) {
                $good_code = strval($matches[1]);
                $item_code = strval($matches[2]);
            } else {
                $good_code = strval($item_sxe->{'Ид'});
            }

            if (!isset($good_min_price[$good_code])) $good_min_price[$good_code] = array();

            foreach ($item_sxe->{'Цены'}->{'Цена'} as $item_price) {
                $code = strval($item_price->{'ИдТипаЦены'});
                if (!isset($good_min_price[$good_code][$price_types[$code]])) {
                    $good_min_price[$good_code][$price_types[$code]] = floatval($item_price->{'ЦенаЗаЕдиницу'});
                } else if ($good_min_price[$good_code][$price_types[$code]] > floatval($item_price->{'ЦенаЗаЕдиницу'})) {
                    $good_min_price[$good_code][$price_types[$code]] = floatval($item_price->{'ЦенаЗаЕдиницу'});
                }

                if ($item_code) {
                    $item = ShItems::findOne(['code' => $item_code]);
                    if ($item) {
                        $price_item = new ShPriceItem();
                        $price_item->items_id = $item->id;
                        $price_item->price_types_id = $price_types[$code];
                        $price_item->price = floatval($item_price->{'ЦенаЗаЕдиницу'});
                        $price_item->save();
                    }
                }
            }
        }

        foreach ($good_min_price as $good_code => $good_prices) {
            $good = ShGoods::findOne(['code' => $good_code]);
            if ($good) {
                foreach ($good_prices as $price_types_id => $good_price) {
                    $price_good = new ShPriceGood();
                    $price_good->goods_id = $good->id;
                    $price_good->price_types_id = $price_types_id;
                    $price_good->price = $good_price;
                    $price_good->save();
                }
            }
        }

    }
}