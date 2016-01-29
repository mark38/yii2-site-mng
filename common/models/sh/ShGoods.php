<?php

namespace common\models\sh;

use Yii;
use common\models\main\Links;

/**
 * This is the model class for table "mod_sh_goods".
 *
 * @property integer $id
 * @property integer $groups_id
 * @property integer $links_id
 * @property string $code
 * @property string $good
 *
 * @property ShGroups $group
 * @property Links $link
 * @property ShPriceGood $price
 */
class ShGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['groups_id', 'links_id'], 'integer'],
            [['links_id'], 'required'],
            [['code', 'good'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groups_id' => 'Groups ID',
            'links_id' => 'Links ID',
            'code' => 'Code',
            'good' => 'Good',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(ShGroups::className(), ['id' => 'groups_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(ShPriceGood::className(), ['goods_id' => 'id']);
    }

    public function getItems()
    {
        return $this->hasMany(ShItems::className(), ['goods_id' => 'id'])->where(['state' => 1]);
    }

    public function getCharacteristics()
    {
        return $this->hasMany(ShItemCharacteristics::className(), ['items_id' => 'id'])->groupBy(['value'])->orderBy(['value' => SORT_ASC])->via('items');
    }

    public function getMinPriceItem()
    {
        return $this->hasOne(ShPriceItem::className(), ['items_id' => 'id'])->select(['min(mod_sh_price_item.price) as min_price', 'mod_sh_price_item.*'])->via('items');
    }

}
