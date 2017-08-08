<?php

namespace common\models\shop;

use Yii;
use common\models\main\Links;

/**
 * This is the model class for table "shop_group_properties".
 *
 * @property integer $id
 * @property integer $shop_groups_id
 * @property integer $shop_properties_id
 * @property integer $state
 *
 * @property ShopGroups $shopGroup
 * @property ShopProperties $shopProperty
 */
class ShopGroupProperties extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_group_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_groups_id', 'shop_properties_id', 'state'], 'integer'],
            [['shop_groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopGroups::className(), 'targetAttribute' => ['shop_groups_id' => 'id']],
            [['shop_properties_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopProperties::className(), 'targetAttribute' => ['shop_properties_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_groups_id' => 'Shop Groups ID',
            'shop_properties_id' => 'Shop Properties ID',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGroup()
    {
        return $this->hasOne(ShopGroups::className(), ['id' => 'shop_groups_id']);
    }

    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id'])->via('shopGroup');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopProperty()
    {
        return $this->hasOne(ShopProperties::className(), ['id' => 'shop_properties_id']);
    }
}
