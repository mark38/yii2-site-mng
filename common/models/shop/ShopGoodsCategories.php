<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_goods_categories".
 *
 * @property int $id
 * @property int $shop_goods_id ID товара
 * @property int $shop_categories_id ID категории
 *
 * @property ShopCategories $shopCategories
 * @property ShopGoods $shopGoods
 */
class ShopGoodsCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_goods_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_goods_id', 'shop_categories_id'], 'integer'],
            [['shop_categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopCategories::className(), 'targetAttribute' => ['shop_categories_id' => 'id']],
            [['shop_goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopGoods::className(), 'targetAttribute' => ['shop_goods_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_goods_id' => 'ID товара',
            'shop_categories_id' => 'ID категории товара',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCategory()
    {
        return $this->hasOne(ShopCategories::className(), ['id' => 'shop_categories_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
    }
}
