<?php

use yii\db\Migration;

/**
 * Class m200212_150432_add_picture_to_gallery_images_table
 */
class m200212_150432_add_picture_to_gallery_images_table extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('gallery_images', 'picture', $this->text());
    }

    public function down()
    {
        echo "m200116_081937_add_virtual_url_to_links cannot be reverted.\n";

        $this->dropColumn('gallery_images', 'picture');
    }
}
