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
        $this->addColumn('gallery_types', 'picture_params', $this->text());
        $this->addColumn('gallery_images', 'picture', $this->text());

        $galleryTypes = \common\models\gallery\GalleryTypes::find()->all();
        /** @var \common\models\gallery\GalleryTypes $galleryType */
        foreach ($galleryTypes as $galleryType) {
            $pictureParams = array(
                'original' => array(
                    'width' => 880,
                    'height' => 880,
                    'path' => '/'.$galleryType->name.'/880x880/',
                ),
                '1200px' => array(
                    'width' => 278,
                    'height' => 278,
                    'path' => '/'.$galleryType->name.'/278x278/',
                ),
                '992px' => array(
                    'width' => 220,
                    'height' => 220,
                    'path' => '/'.$galleryType->name.'/220x220/',
                ),
                '768px' => array(
                    'width' => 180,
                    'height' => 180,
                    'path' => '/'.$galleryType->name.'/180x180/',
                ),
                '480px' => array(
                    'width' => 160,
                    'height' => 160,
                    'path' => '/'.$galleryType->name.'/160x160/',
                ),
                '320px' => array(
                    'width' => 150,
                    'height' => 150,
                    'path' => '/'.$galleryType->name.'/150x150/',
                )
            );

            $galleryType->picture_params = json_encode($pictureParams);
            $galleryType->save();
        }
    }

    public function down()
    {
        $this->dropColumn('gallery_types', 'picture_params');
        $this->dropColumn('gallery_images', 'picture');
    }
}
