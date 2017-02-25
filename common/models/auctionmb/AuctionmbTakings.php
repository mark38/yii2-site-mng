<?php

namespace common\models\auctionmb;

use Yii;

/**
 * This is the model class for table "auctionmb_takings".
 *
 * @property integer $id
 * @property integer $auctionmb_users_id
 * @property integer $auctionmb_rates_id
 * @property integer $auctionmb_payment_systems_id
 * @property integer $auctionmb_taking_types_id
 * @property string $paid
 * @property integer $bets
 * @property string $comment
 *
 * @property AuctionmbTakingTypes $auctionmbTakingType
 * @property AuctionmbPaymentSystems $auctionmbPaymentSystem
 * @property AuctionmbRates $auctionmbRate
 * @property AuctionmbUsers $auctionmbUser
 */
class AuctionmbTakings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_takings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auctionmb_users_id', 'auctionmb_rates_id', 'auctionmb_payment_systems_id', 'auctionmb_taking_types_id', 'bets'], 'integer'],
            [['paid'], 'number'],
            [['comment'], 'string', 'max' => 512],
            [['auctionmb_taking_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbTakingTypes::className(), 'targetAttribute' => ['auctionmb_taking_types_id' => 'id']],
            [['auctionmb_payment_systems_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbPaymentSystems::className(), 'targetAttribute' => ['auctionmb_payment_systems_id' => 'id']],
            [['auctionmb_rates_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbRates::className(), 'targetAttribute' => ['auctionmb_rates_id' => 'id']],
            [['auctionmb_users_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbUsers::className(), 'targetAttribute' => ['auctionmb_users_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auctionmb_users_id' => 'Auctionmb Users ID',
            'auctionmb_rates_id' => 'Auctionmb Rates ID',
            'auctionmb_payment_systems_id' => 'Auctionmb Payment Systems ID',
            'auctionmb_taking_types_id' => 'Auctionmb Taking Types ID',
            'paid' => 'Paid',
            'bets' => 'Bets',
            'comment' => 'Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbTakingType()
    {
        return $this->hasOne(AuctionmbTakingTypes::className(), ['id' => 'auctionmb_taking_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbPaymentSystem()
    {
        return $this->hasOne(AuctionmbPaymentSystems::className(), ['id' => 'auctionmb_payment_systems_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbRate()
    {
        return $this->hasOne(AuctionmbRates::className(), ['id' => 'auctionmb_rates_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbUser()
    {
        return $this->hasOne(AuctionmbUsers::className(), ['id' => 'auctionmb_users_id']);
    }
}
