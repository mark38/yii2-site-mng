<?php

namespace common\models\gl;

use Yii;

/**
 * This is the model class for table "mod_gl_imgs".
 *
 * @property integer $id
 * @property integer $groups_id
 * @property string $img_small
 * @property string $img_large
 * @property string $basename_src
 * @property string $title
 * @property string $url
 * @property integer $seq
 *
 * @property Links[] $links
 * @property GlGroups[] $glGroups
 * @property GlGroups $group
 */
class GlImgs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_gl_imgs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['groups_id', 'seq'], 'integer'],
            [['img_small', 'img_large', 'basename_src', 'title', 'url'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groups_id' => 'Groups ID',
            'img_small' => 'Img Small',
            'img_large' => 'Img Large',
            'basename_src' => 'Basename Src',
            'title' => 'Title',
            'url' => 'Url',
            'seq' => 'Seq',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Links::className(), ['gl_imgs_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlGroups()
    {
        return $this->hasMany(GlGroups::className(), ['imgs_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(GlGroups::className(), ['id' => 'groups_id']);
    }

    public function getType()
    {
        /*return $this->hasOne(GlGroups::className(), ['id' => 'groups_id'])
            ->viaTable('mod_gl_types', ['id' => 'mod_gl_groups.types_id']);*/
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            unlink(Yii::getAlias('@webroot').$this->img_small);
            unlink(Yii::getAlias('@webroot').$this->img_large);

            return true;
        } else {
            return false;
        }
    }

    public static function findLastSequence($groups_id)
    {
        $q = static::find()->where(['groups_id' => $groups_id])->orderBy(['seq' => SORT_DESC])->one();
        return ($q ? $q->seq : 0);
    }

    public function reSort($groups_id)
    {
        foreach (self::find()->where(['groups_id' => $groups_id])->orderBy(['seq' => SORT_ASC])->all() as $i => $img) {
            $img->seq = $i += 1;
            $img->save();
        }

        return true;
    }

    public function convertImg($types_id, $src_image, $dst_path)
    {
        if (!file_exists($src_image)) return false;
        $path_parts = pathinfo($src_image);

        if (!is_dir($dst_path)) {
            if (!mkdir($dst_path)) die('Не удалось создать директорию');
        }

        do {
            $filename = '';
            for ( $j = 0; $j < 12; $j++ ) $filename .= chr( rand(97, 122) );

            $img_small = $dst_path.'/'.$filename.'.'.$path_parts['extension'];
            $img_large = $dst_path.'/'.$filename.'-l.'.$path_parts['extension'];
        } while(file_exists($img_small) && file_exists($img_large));

        $type = GlTypes::findOne($types_id);
        $result_large = self::resizeImage($img_large, $src_image, $path_parts['extension'], $type->large_width, $type->large_height, 60);
        $result_small = self::resizeImage($img_small, $src_image, $path_parts['extension'], $type->small_width, $type->small_height, 60);
        if (!$result_small || !$result_large) return false;

        return [
            'img_small' => basename($img_small),
            'img_large' => basename($img_large),
        ];

    }

    public function resizeImage($outfile, $infile, $extention, $neww=null, $newh=null, $quality=null)
    {
        $imgParams = getimagesize($infile);
        if ($neww > 0 && $neww > $imgParams[0]) $neww = $imgParams[0];
        if ($newh > 0 && $newh > $imgParams[1]) $newh = $imgParams[1];

        $quality = $quality !== null ? $quality : 100;
        if ( ! $neww && ! $newh ) {
            $neww = $imgParams[0];
            $newh = $imgParams[1];
        } elseif ( ! $neww ) {
            $neww = ($newh * $imgParams[0]) / $imgParams[1];
        } elseif ( ! $newh ) {
            $newh = ($neww * $imgParams[1]) / $imgParams[0];
        }

        switch ($extention) {
            case "gif": $src_image = ImageCreateFromGIF( $infile ); break;
            case "jpg": $src_image = ImageCreateFromJPEG( $infile ); break;
            case "JPG": $src_image = ImageCreateFromJPEG( $infile ); break;
            case "jpeg": $src_image = ImageCreateFromJPEG( $infile ); break;
            case "png": $src_image = ImageCreateFromPNG( $infile ); break;
            default: return false; break;
        }

        $dst_image = imageCreateTrueColor($neww, $newh);
        if($extention == "gif" or $extention == "png"){
            imageAlphaBlending($dst_image, false);
            imageSaveAlpha($dst_image, true);
            imagecolortransparent($dst_image, imagecolorallocatealpha($dst_image, 0, 0, 0, 127));
        }

        if ( $neww == $newh ) {
            if ( $imgParams[0] < $imgParams[1] ) {
                $src_y = ($imgParams[1] - $imgParams[0]) / 2;
                imagecopyresampled($dst_image,$src_image,0,0,0,$src_y,$neww,$newh,imagesx($src_image),imagesx($src_image));
            } else {
                $src_x = ($imgParams[0] - $imgParams[1] ) / 2;
                imagecopyresampled($dst_image,$src_image,0,0,$src_x,0,$neww,$newh,imagesy($src_image),imagesy($src_image));
            }
        } else {
            imagecopyresampled($dst_image,$src_image,0,0,0,0,$neww,$newh,imagesx($src_image),imagesy($src_image));
        }

        if ($extention == 'png') {
            imagepng($dst_image, $outfile);
        } else {
            imagejpeg($dst_image,$outfile,$quality);
        }

        imagedestroy($src_image);
        imagedestroy($dst_image);

        return true;
    }
}
