<?php

namespace common\models\shop;

use Yii;
use common\models\main\Links;

/**
 * This is the model class for table "shop_groups".
 *
 * @property integer $id
 * @property string $links_id
 * @property string $code
 * @property string $name
 *
 * @property ShopGoods[] $shopGoods
 * @property ShopGroupProperties[] $shopGroupProperties
 * @property Links $link
 */
class ShopGroups extends \yii\db\ActiveRecord
{
    public $groupProperties;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id'], 'integer'],
            [['verification_code', 'name'], 'string', 'max' => 255],
            ['groupProperties', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'links_id' => 'Links ID',
            'verification_code' => 'Verification Code',
            'name' => 'Name',
            'groupProperties' => 'Свойства номенклатуры в группе',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoods()
    {
        return $this->hasMany(ShopGoods::className(), ['shop_groups_id' => 'id']);
    }

    public function getShopGroupProperties()
    {
        return $this->hasMany(ShopGroupProperties::className(), ['shop_groups_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
