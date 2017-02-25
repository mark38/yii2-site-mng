<?php

namespace common\models\auctionmb;

use Yii;

/**
 * This is the model class for table "auctionmb_bets".
 *
 * @property integer $id
 * @property integer $auctionmb_users_id
 * @property integer $auctionmb_id
 * @property integer $created_at
 *
 * @property Auctionmb $auctionmb
 * @property AuctionmbUsers $auctionmbUser
 */
class AuctionmbBets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_bets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auctionmb_users_id', 'auctionmb_id', 'created_at'], 'integer'],
            [['auctionmb_id'], 'exist', 'skipOnError' => true, 'targetClass' => Auctionmb::className(), 'targetAttribute' => ['auctionmb_id' => 'id']],
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
            'auctionmb_id' => 'Auctionmb ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmb()
    {
        return $this->hasOne(Auctionmb::className(), ['id' => 'auctionmb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbUser()
    {
        return $this->hasOne(AuctionmbUsers::className(), ['id' => 'auctionmb_users_id']);
    }
}
