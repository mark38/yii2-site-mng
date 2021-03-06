<?php

namespace app\modules\shop\controllers;

use backend\models\Helpers;
use common\models\gallery\GalleryImages;
use common\models\gallery\GalleryTypes;
use Yii;
use yii\console\Controller;
use backend\modules\shop\models\Import;
use common\models\main\Links;

/**
 * Default controller for the `shop` module
 */
class DefaultController extends Controller
{
    /**
     * Parser import.xml from ic
     * @param $import_xml
     * @return bool
     */
    public function actionImport($import_xml)
    {
        $start = microtime(true);

        $f = fopen(Yii::getAlias('@backend').Yii::$app->params['shop']['upload_dir'].'/upload.log', 'a');

        $model = new Import();
        $model->parser($import_xml);

        $time = microtime(true) - $start;
        fprintf($f, date('d.m.Y H:i')." Обработка из консоли файла Import({$import_xml}) выпонялась %.4F сек.\n", $time);
        fclose($f);

        return true;
    }

    /**
     * @param string $file (format in utf8: anchor;url;name;amount)
     * @return bool
     */
    public function actionSynchronizationUrl($file)
    {
        $fp = fopen($file, 'rt');
        if ($fp) {
            while (!feof($fp)) {
                $strArr = preg_split("/;/", fgets($fp));
                if ($strArr[0]) {
                    echo $strArr[0]."\n";

                    $link = Links::find()
                        ->select(['id', 'anchor', 'url', 'count(id)'])
                        ->groupBy(['anchor'])
                        ->where(['anchor' => $strArr[0]])
                        ->having(['=', 'count(id)', '1'])
                        ->one();

                    if ($link && $strArr[1] && $strArr[2]) {
                        $link->url = $strArr[1];
                        $link->name = $strArr[2];
                        $link->update();

                        echo "Update ".$link->id."\n";
                    }
                }
            }
        }

        /*foreach ($srcLinks as $link) {
            echo $link->id.' | '.$link->url."\n";
        }*/

        echo "\n\n\n===============\n\n\n\n".count($links)."\n";

        return true;
    }

    public function actionConvertImage()
    {
        $start = microtime(true);

        $model = new GalleryImages();
        $helper = new Helpers();
        $images = GalleryImages::find()->joinWith('galleryGroup')->joinWith('galleryType')->where(['gallery_types_id' => 4])->all();
        $images = GalleryImages::find()->where(['gallery_groups_id' => [559,560,561,562,563,564,565,566,567,568,569,570,571,5843,572,573,574,575,576,577,578,579,5844,5839,5842]])->all();

        if ($images) {
            $totalAmount = count($images);
            $num = 0;
            /** @var GalleryImages $image */
            foreach ($images as $image) {
                $num += 1;
                echo "Обработка изображения: $num/$totalAmount\n";

                $smallFilePath = Yii::getAlias('@frontend/web').$image->small;
                $largeFilePath = Yii::getAlias('@frontend/web').$image->large;
                $extension = pathinfo($largeFilePath, PATHINFO_EXTENSION);

                if ($extension == 'gif') continue;

                $pictureParams = json_decode($image->galleryGroup->galleryType->picture_params);
                $picture = json_decode($image->picture);

                foreach ($pictureParams as $key => $media) {
                    $fullPath = preg_replace('/\/$/', '', Yii::getAlias('@frontend/web').$media->path);
                    $mediaPath = preg_replace('/\/$/', '', $media->path);
                    $helper->makeDirectory($fullPath);

                    if (!isset($picture->$key)) {
                        do {
                            $fileName = '';
                            for ($j = 0; $j < 8; $j++ ) {
                                if (rand(0,1) == 0) {
                                    $fileName .= chr(rand(97, 122));
                                } else {
                                    $fileName .= chr(rand(65, 90));
                                }
                            }
                        } while(file_exists($fullPath.'/'.$fileName.'.'.$extension));

                        $picture[$key] = array(
                            'img' => $mediaPath.'/'.$fileName.'.'.$extension,
                            'webp' => $mediaPath.'/'.$fileName.'.webp',
                        );

                        $model->resizeAndConvertImageWebP($media->width, $media->height, $largeFilePath, $fullPath, $fileName, $extension);
                    }
                }

                $image->picture = json_encode($picture);
                $image->save();
                unset($picture);
            }
        }

        $time = microtime(true) - $start;
        printf("Скрипт выполнялся %.4F сек.\n", $time);
    }
}
