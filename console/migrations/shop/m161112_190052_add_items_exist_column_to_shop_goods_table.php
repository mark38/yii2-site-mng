<?php

use yii\db\Migration;

/**
 * Handles adding items_exist to table `shop_goods`.
 */
class m161112_190052_add_items_exist_column_to_shop_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_goods', 'items_exist', $this->boolean()->defaultValue(false));

        $query = new \yii\db\Query();
        $shopGoods = $query->select(['id'])->from('shop_goods')->createCommand()->queryAll();
        foreach ($shopGoods as $i => $good) {
            $shopItems = $query->select(['id'])->from('shop_items')->where(['shop_goods_id' => $good['id']])->createCommand()->queryOne();
            if ($shopItems) {
                Yii::$app->db->createCommand('update shop_goods set items_exist = 1 where id = '.$good['id'])->execute();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('shop_goods', 'items_exist');
    }
}
