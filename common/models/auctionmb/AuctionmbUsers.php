<?php

namespace common\models\auctionmb;

use Yii;
use common\models\User;

/**
 * This is the model class for table "auctionmb_users".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $friend_user_id
 * @property integer $balance_bets
 * @property string $name
 * @property string $phone
 *
 * @property Auctionmb[] $auctionmbs
 * @property AuctionmbBets[] $auctionmbBets
 * @property AuctionmbTakings[] $auctionmbTakings
 * @property User $friendUser
 * @property User $user
 */
class AuctionmbUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'friend_user_id', 'balance_bets'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 255],
            [['friend_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['friend_user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'friend_user_id' => 'Friend User ID',
            'balance_bets' => 'Balance Bets',
            'name' => 'Name',
            'phone' => 'Phone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbs()
    {
        return $this->hasMany(Auctionmb::className(), ['auctionmb_users_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbBets()
    {
        return $this->hasMany(AuctionmbBets::className(), ['auctionmb_users_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbTakings()
    {
        return $this->hasMany(AuctionmbTakings::className(), ['auctionmb_users_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendUser()
    {
        return $this->hasOne(User::className(), ['id' => 'friend_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
