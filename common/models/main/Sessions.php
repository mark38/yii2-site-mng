<?php

namespace common\models\main;

use Yii;
use common\models\shop\ShopGoodFavorites;

/**
 * This is the model class for table "sessions".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ShopGoodFavorites[] $shopGoodFavorites
 */
class Sessions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sessions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoodFavorites()
    {
        return $this->hasMany(ShopGoodFavorites::className(), ['sessions_id' => 'id']);
    }
}
