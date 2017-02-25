<?php

namespace common\models\auctionmb;

use common\models\main\Links;
use Yii;

/**
 * This is the model class for table "auctionmb_lots".
 *
 * @property integer $id
 * @property integer $links_id
 * @property integer $seconds
 * @property integer $bets
 *
 * @property Auctionmb[] $auctionmbs
 * @property Links $link
 */
class AuctionmbLots extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_lots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id', 'seconds', 'bets'], 'integer'],
            [['links_id'], 'exist', 'skipOnError' => true, 'targetClass' => Links::className(), 'targetAttribute' => ['links_id' => 'id']],
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
            'seconds' => 'Seconds',
            'bets' => 'Bets',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbs()
    {
        return $this->hasMany(Auctionmb::className(), ['auctionmb_lots_id' => 'id']);
    }

    public function getAuctionmbActive()
    {
        return $this->hasOne(Auctionmb::className(), ['auctionmb_lots_id' => 'id'])->where(['auctionmb.state' => true])->orderBy(['auctionmb.id' => SORT_DESC])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
