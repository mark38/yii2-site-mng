<?php

namespace common\models\sh;

use Yii;
use common\models\main\Links;

/**
 * This is the model class for table "mod_sh_groups".
 *
 * @property integer $id
 * @property integer $links_id
 * @property string $code
 * @property string $groupname
 *
 * @property Links $link
 */
class ShGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id'], 'integer'],
            [['code', 'groupname'], 'string', 'max' => 1024]
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
            'code' => 'Code',
            'groupname' => 'Groupname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
