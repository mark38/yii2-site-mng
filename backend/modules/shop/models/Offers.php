<?php

namespace app\modules\shop\models;

use common\models\shop\ShopGoods;
use common\models\shop\ShopItems;
use common\models\shop\ShopPriceGood;
use common\models\shop\ShopPriceItem;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\shop\ShopPriceTypes;
/**
 * Site controller
 */
class Offers extends Model
{
    private $offers_file;

    public function parser($offers_file)
    {
        $this->offers_file = $offers_file;
        $sxe = simplexml_load_file($offers_file);

        $price_types_sxe = $sxe->xpath('/КоммерческаяИнформация/ПакетПредложений/ТипыЦен/ТипЦены');
        if (count($price_types_sxe)) $this->parserPriceTypes($price_types_sxe);

        $offers_sxe = $sxe->xpath('/КоммерческаяИнформация/ПакетПредложений/Предложения/Предложение');
        if (count($offers_sxe)) $this->parserOffers($offers_sxe);
    }

    private function parserPriceTypes($price_types_sxe)
    {
        foreach ($price_types_sxe as $item) {
            $price_type = ShopPriceTypes::findOne(['verification_code' => $item->{'Ид'}]);
            if (!$price_type) {
                $price_type = new ShopPriceTypes();
                $price_type->verification_code = strval($item->{'Ид'});
            }
            $price_type->name = strval($item->{'Наименование'});
            $price_type->save();
        }

        return true;
    }

    private function parserOffers($offers_sxe)
    {
        /* Поиск и удаление цен для товара и его характеристик */
        foreach ($offers_sxe as $item_sxe) {
            if (preg_match('/(.+)#(.+)/', $item_sxe->{'Ид'}, $matches)) {
                $good_verification_code = strval($matches[1]);
                $item_verification_code = strval($matches[2]);
                $prices_item = ShopPriceItem::find()->innerJoinWith('shopItem')->where(['shop_items.verification_code' => $item_verification_code])->all();
                if ($prices_item) ShopPriceItem::deleteAll(['id' => ArrayHelper::getColumn($prices_item, 'id')]);
            } else {
                $good_verification_code = strval($item_sxe->{'Ид'});
            }
            $price_good = ShopPriceGood::find()->innerJoinWith('shopGood')->where(['shop_goods.verification_code' => $good_verification_code])->all();
            if ($price_good) ShopPriceGood::deleteAll(['id' => ArrayHelper::getColumn($price_good, 'id')]);
        }

        $price_types = ArrayHelper::map(ShopPriceTypes::find()->all(), 'verification_code', 'id');
        $good_min_price = array();
        foreach ($offers_sxe as $item_sxe) {
            $item_verification_code = false;
            if (preg_match('/(.+)#(.+)/', $item_sxe->{'Ид'}, $matches)) {
                $good_verification_code = strval($matches[1]);
                $item_verification_code = strval($matches[2]);
            } else {
                $good_verification_code = strval($item_sxe->{'Ид'});
            }

            if (!isset($good_min_price[$good_verification_code])) $good_min_price[$good_verification_code] = array();

            foreach ($item_sxe->{'Цены'}->{'Цена'} as $item_price) {
                $verification_code = strval($item_price->{'ИдТипаЦены'});
                if (!isset($good_min_price[$good_verification_code][$price_types[$verification_code]])) {
                    $good_min_price[$good_verification_code][$price_types[$verification_code]] = floatval($item_price->{'ЦенаЗаЕдиницу'});
                } else if ($good_min_price[$good_verification_code][$price_types[$verification_code]] > floatval($item_price->{'ЦенаЗаЕдиницу'})) {
                    $good_min_price[$good_verification_code][$price_types[$verification_code]] = floatval($item_price->{'ЦенаЗаЕдиницу'});
                }

                if ($item_verification_code) {
                    $item = ShopItems::findOne(['verification_code' => $item_verification_code]);
                    if ($item) {
                        $price_item = new ShopPriceItem();
                        $price_item->shop_items_id = $item->id;
                        $price_item->shop_price_types_id = $price_types[$verification_code];
                        $price_item->price = floatval($item_price->{'ЦенаЗаЕдиницу'});
                        $price_item->save();
                    }
                }
            }
        }

        foreach ($good_min_price as $good_verification_code => $good_prices) {
            $good = ShopGoods::findOne(['verification_code' => $good_verification_code]);
            if ($good) {
                foreach ($good_prices as $price_types_id => $good_price) {
                    $price_good = new ShopPriceGood();
                    $price_good->shop_goods_id = $good->id;
                    $price_good->shop_price_types_id = $price_types_id;
                    $price_good->price = $good_price;
                    $price_good->save();
                }
            }
        }

    }
}