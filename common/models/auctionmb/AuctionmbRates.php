<?php

namespace common\models\auctionmb;

use Yii;

/**
 * This is the model class for table "auctionmb_rates".
 *
 * @property integer $id
 * @property string $cost
 * @property integer $bets
 * @property integer $state
 * @property integer $seq
 *
 * @property AuctionmbTakings[] $auctionmbTakings
 */
class AuctionmbRates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_rates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cost'], 'number'],
            [['bets', 'state', 'seq'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cost' => 'Cost',
            'bets' => 'Bets',
            'state' => 'State',
            'seq' => 'Seq',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbTakings()
    {
        return $this->hasMany(AuctionmbTakings::className(), ['auctionmb_rates_id' => 'id']);
    }
}
