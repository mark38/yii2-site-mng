<?php

namespace common\models\sh;

use common\models\gl\GlGroups;
use Yii;

/**
 * This is the model class for table "mod_sh_good_imgs".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $gl_groups_id
 *
 * @property GlGroups $glGroups
 * @property ShGoods $goods
 */
class ShGoodImgs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_good_imgs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'gl_groups_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'gl_groups_id' => 'Gl Groups ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlGroup()
    {
        return $this->hasOne(GlGroups::className(), ['id' => 'gl_groups_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGood()
    {
        return $this->hasOne(ShGoods::className(), ['id' => 'goods_id']);
    }
}
