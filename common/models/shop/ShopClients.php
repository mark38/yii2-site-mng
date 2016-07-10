<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_clients".
 *
 * @property integer $id
 * @property string $fio
 * @property string $city
 * @property string $street
 * @property string $home_number
 * @property string $flat_number
 * @property string $phone
 * @property string $email
 * @property string $comment
 *
 * @property ShopClientCarts[] $shopClientCarts
 */
class ShopClients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fio', 'phone', 'city', 'street', 'home_number'], 'required'],
            [['fio', 'city', 'street', 'home_number', 'flat_number', 'phone', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['comment'], 'string', 'max' => 2048],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Ваше имя',
            'city' => 'Город',
            'street' => 'Улица',
            'home_number' => 'Номер дома',
            'flat_number' => 'Номер квартиры / офиса / доп. информация',
            'phone' => 'Контактный телефон',
            'email' => 'Электронная почта',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopClientCarts()
    {
        return $this->hasMany(ShopClientCarts::className(), ['shop_clients_id' => 'id']);
    }
}
