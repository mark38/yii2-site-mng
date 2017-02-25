<?php

namespace common\models\auctionmb;

use Yii;

/**
 * This is the model class for table "auctionmb_taking_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 * @property AuctionmbTakings[] $auctionmbTakings
 */
class AuctionmbTakingTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_taking_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1024],
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
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbTakings()
    {
        return $this->hasMany(AuctionmbTakings::className(), ['auctionmb_taking_types_id' => 'id']);
    }
}
