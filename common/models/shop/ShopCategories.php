<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_categories".
 *
 * @property int $id
 * @property string $name Наименование категории
 *
 * @property ShopGoodsCategories[] $shopGoodsCategories
 */
class ShopCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'name' => 'Наименование'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoodsCategories()
    {
        return $this->hasMany(ShopGoodsCategories::className(), ['shop_categories_id' => 'id']);
    }
}
