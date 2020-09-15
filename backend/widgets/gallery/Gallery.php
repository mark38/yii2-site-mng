<?php
namespace backend\widgets\gallery;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\imagine\Image;
use Imagine\Gd;
use yii\web\UploadedFile;

class Gallery extends Model
{
    public $tableTypes = '{{%gallery_types}}';
    public $tableGroups = '{{%gallery_groups}}';
    public $tableImages = '{{%gallery_images}}';

    public function getType($name)
    {
        $type = (new Query())->select(['*'])
            ->from($this->tableTypes)
            ->where(['name' => $name])
            ->createCommand()
            ->queryOne();

        return $type;
    }

    public function addGroup($types_id)
    {
        $query = new Query();
        $query->createCommand()->insert($this->tableGroups, [
            'gallery_types_id' => $types_id
        ])->execute();

        return Yii::$app->db->getLastInsertID();
    }

    public function uploadImage($route, $gallery_types_id, $gallery_groups_id)
    {
        $type = (new Query())->select('*')
            ->from($this->tableTypes)
            ->where(['id' => $gallery_types_id])
            ->createCommand()
            ->queryOne();

        $route = preg_replace('/\/$/', '', $route);
        $destination = preg_replace('/\/$/', '', $type['destination']);

        if (!is_dir($route.$destination)) {
            if (!mkdir($route.$destination)) {
                return [
                    'success' => false,
                    'message' => 'Failed to add directory',
                ];
            }
        }

        $imageFile = UploadedFile::getInstanceByName('image');

        $image = (new Query())->select('*')
            ->from($this->tableImages)
            ->where(['gallery_groups_id' => $gallery_groups_id])
            ->andWhere(['name' => $imageFile->baseName.'.'.$imageFile->extension])
            ->createCommand()
            ->queryOne();
        if ($image) {
            return [
                'success' => false,
                'message' => 'File downloaded previously in this group'
            ];
        }

        $src_file = $route.$destination.'/'.$imageFile->baseName.'.'.$imageFile->extension;
        $imageFile->saveAs($src_file, true);

        $size = $this->getSize($src_file, $type);

        $image_small = $this->renderFilename($route.$destination, $imageFile->baseName.'-s', $imageFile->extension);
        $image_large = $this->renderFilename($route.$destination, $imageFile->baseName.'-l', $imageFile->extension);

        Image::$driver = [Image::DRIVER_GD2];

        Image::thumbnail($src_file, $size['small_width'], $size['small_height'])->save($route.$destination.'/'.$image_small.'.'.$imageFile->extension, ['quality' => $type['quality']]);
        Image::thumbnail($src_file, $size['large_width'], $size['large_height'])->save($route.$destination.'/'.$image_large.'.'.$imageFile->extension, ['quality' => $type['quality']]);

        unlink($src_file);

        $query = new Query();
        $query->createCommand()->insert($this->tableImages, [
            'gallery_groups_id' => $gallery_groups_id,
            'small' => $destination.'/'.$image_small.'.'.$imageFile->extension,
            'large' => $destination.'/'.$image_large.'.'.$imageFile->extension,
            'name' => $imageFile->baseName.'.'.$imageFile->extension,
            'seq' => ($this->getLastSequence($gallery_groups_id) + 1),
        ])->execute();

        return [
            'success' => true,
            'gallery_images_id' => Yii::$app->db->getLastInsertID(),
            'gallery_groups_id' => $gallery_groups_id,
            'small' => $destination.'/'.$image_small.'.'.$imageFile->extension,
            'large' => $destination.'/'.$image_large.'.'.$imageFile->extension,
        ];
    }

    public function changeDefaultImage($gallery_groups_id)
    {
        $default_image = (new Query())->select('*')
            ->from($this->tableImages)
            ->where(['gallery_groups_id' => $gallery_groups_id, 'seq' => 1])
            ->createCommand()
            ->queryOne();

        $group = (new Query())->select('*')
            ->from($this->tableGroups)
            ->where(['id' => $gallery_groups_id, 'gallery_images_id' => $default_image['id']])
            ->createCommand()
            ->queryOne();

        if (!isset($group['id'])) {
            Yii::$app->db->createCommand()->update($this->tableGroups, ['gallery_images_id' => $default_image['id']], 'id = '.$gallery_groups_id)->execute();
        }

        return true;
    }

    public function getSize($src_file, $type)
    {
        list($img_w,$img_h) = getimagesize($src_file);
        $small_width = $type['small_width'];
        $small_height = $type['small_height'];
        $large_width = $type['large_width'];
        $large_height = $type['large_height'];

        if ($small_width == 0 && $small_height == 0) {
            $small_width = $img_w;
            $small_height = $img_h;
        } elseif ($small_height == 0) {
            $small_height = ($small_width * $img_h) / $img_w;
        } elseif ($small_width == 0) {
            $small_width = ($small_height * $img_w) / $img_h;
        }

        if ($small_width > $img_w || $small_height > $img_h) {
            $small_width = $img_w;
            $small_height = $img_h;
        }

        if ($large_width == 0 && $large_height == 0) {
            $large_width = $img_w;
            $large_height = $img_h;
        } elseif ($large_height == 0) {
            $large_height = ($large_width * $img_h) / $img_w;
        } elseif ($large_width == 0) {
            $large_width = ($large_height * $img_w) / $img_h;
        }

        if ($large_width > $img_w || $large_height > $img_h) {
            $large_width = $img_w;
            $large_height = $img_h;
        }

        return [
            'small_width' => (int)$small_width,
            'small_height' => (int)$small_height,
            'large_width' => (int)$large_width,
            'large_height' => (int)$large_height
        ];
    }

    public function renderFilename($dir, $baseName, $extension)
    {
        /*do {
            $filename = '';
            for ( $j = 0; $j < 12; $j++ ) $filename .= chr( rand(97, 122) );
        } while(file_exists($dir.'/'.$filename.'.'.$extension));*/

        $filename = $baseName;
        if (file_exists($dir.'/'.$filename.$extension)) {
            do {
                $filename = '';
                for ( $j = 0; $j < 12; $j++ ) $filename .= chr( rand(97, 122) );
            } while(file_exists($dir.'/'.$filename.'.'.$extension));
        }
        /*while (file_exists($dir.'/'.$filename.$extension)) {
            $filename = '';
            for ( $j = 0; $j < 12; $j++ ) $filename .= chr( rand(97, 122) );
        }*/

        return $filename;
    }

    public function getLastSequence($gallery_groups_id)
    {
        $query = (new Query())->select(['seq'])
            ->from($this->tableImages)
            ->where(['gallery_groups_id' => $gallery_groups_id])
            ->orderBy(['seq' => SORT_DESC])
            ->createCommand()
            ->queryOne();

        if ($query) {
            return $query['seq'];
        }

        return 0;
    }

    public function getImages($gallery_groups_id)
    {
        $images = (new Query())->select('*')
            ->from($this->tableImages)
            ->where(['gallery_groups_id' => $gallery_groups_id])
            ->orderBy(['seq' => SORT_ASC])
            ->createCommand()
            ->queryAll();

        return $images;
    }

    public function getImage($id)
    {
        $image = (new Query())->select('*')
            ->from($this->tableImages)
            ->where(['id' => $id])
            ->orderBy(['seq' => SORT_ASC])
            ->createCommand()
            ->queryOne();

        return $image;
    }

    public function deleteImage($route, $id)
    {
        $image = $this->getImage($id);
        if ($image) {
            if (isset($image['small'])) {
                unlink($route.$image['small']);
            }
            if (isset($image['large'])) {
                unlink($route.$image['large']);
            }

            (new Query())->createCommand()->delete($this->tableImages, [
                'id' => $id
            ])->execute();
        }

        $this->resortImages($image['gallery_groups_id']);

        return true;
    }

    public function resortImages($gallery_groups_id)
    {
        $images = (new Query())->select(['*'])
            ->from($this->tableImages)
            ->where(['gallery_groups_id' => $gallery_groups_id])
            ->orderBy(['seq' => SORT_ASC])
            ->createCommand()
            ->queryAll();

        foreach ($images as $seq => $image) {
            Yii::$app->db->createCommand()->update($this->tableImages, ['seq' => ($seq+1)], 'id = '.$image['id'])->execute();
        }

        return true;
    }
}