<?php
namespace common\models\main;

use Yii;
use yii\db\ActiveRecord;

/**
 * Contents model
 *
 * @property integer $id
 * @property string $category
 * @property string $comment
 * @property integer $seq
 */

class Categories extends ActiveRecord
{
    public static function tableName()
    {
        return 'categories';
    }
}