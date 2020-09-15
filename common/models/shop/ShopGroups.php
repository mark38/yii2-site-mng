<?php

namespace common\models\shop;

use Yii;
use common\models\main\Links;

/**
 * This is the model class for table "shop_groups".
 *
 * @property integer $id
 * @property integer $links_id
 * @property string $verification_code
 * @property string $name
 * @property integer $parent_id
 *
 * @property ShopGoods[] $shopGoods
 * @property ShopGroupProperties[] $shopGroupProperties
 * @property ShopGroups $parent
 * @property ShopGroups[] $shopGroups
 * @property ShopGroups[] $childGroups
 * @property Links $link
 * @property Links[] $activeChildLinks
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
            [['links_id', 'parent_id'], 'integer'],
            [['verification_code', 'name'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopGroups::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['links_id'], 'exist', 'skipOnError' => true, 'targetClass' => Links::className(), 'targetAttribute' => ['links_id' => 'id']],
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
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoods()
    {
        return $this->hasMany(ShopGoods::className(), ['shop_groups_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGroupProperties()
    {
        return $this->hasMany(ShopGroupProperties::className(), ['shop_groups_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ShopGroups::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGroups()
    {
        return $this->hasMany(ShopGroups::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    public function getActiveChildLinks()
    {
        return $this->hasMany(Links::className(), ['parent' => 'id'])->via('link')->where(['state' => true])->orderBy(['seq' => SORT_ASC]);
    }

    public function getChildGroups()
    {
        return $this->hasMany($this::className(), ['links_id' => 'id'])->via('activeChildLinks');
    }
}
