<?php

namespace common\models\auctionmb;

use common\models\main\Links;
use Yii;

/**
 * This is the model class for table "auctionmb".
 *
 * @property integer $id
 * @property integer $auctionmb_lots_id
 * @property integer $auctionmb_users_id
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuctionmbUsers $auctionmbUser
 * @property AuctionmbLots $auctionmbLot
 * @property Links $link
 * @property AuctionmbBets[] $auctionmbBets
 */
class Auctionmb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auctionmb_lots_id', 'auctionmb_users_id', 'state', 'created_at', 'updated_at'], 'integer'],
            [['auctionmb_users_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbUsers::className(), 'targetAttribute' => ['auctionmb_users_id' => 'id']],
            [['auctionmb_lots_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbLots::className(), 'targetAttribute' => ['auctionmb_lots_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auctionmb_lots_id' => 'Auctionmb Lots ID',
            'auctionmb_users_id' => 'Auctionmb Users ID',
            'state' => 'State',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbUser()
    {
        return $this->hasOne(AuctionmbUsers::className(), ['id' => 'auctionmb_users_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbLot()
    {
        return $this->hasOne(AuctionmbLots::className(), ['id' => 'auctionmb_lots_id']);
    }

    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id'])->via('auctionmbLot');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbBets()
    {
        return $this->hasMany(AuctionmbBets::className(), ['auctionmb_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    public function getLastBet() {
        return $this->auctionmbBets ? $this->auctionmbBets[0] : null;
    }

    public function getLastBetId() {
        return $this->auctionmbBets ? $this->auctionmbBets[0]->id : null;
    }

    public function getLeftTime() {
        if (!$this->state) {
            return 0;
        }
        $seconds = $this->auctionmbLot ? $this->auctionmbLot->seconds : 0;
        $lastBetTime = $this->auctionmbBets ? $this->auctionmbBets[0]->created_at : 0;
        $createdTime = $this->created_at;
        return $lastBetTime ? floor(($lastBetTime + $seconds - time())) : floor(($createdTime + $seconds - time()));
    }


}
