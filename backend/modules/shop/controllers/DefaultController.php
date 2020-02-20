<?php

namespace app\modules\shop\controllers;

use app\modules\shop\models\Contragents3;
use app\modules\shop\models\Offers3;
use app\modules\shop\models\Prices3;
use backend\modules\shop\models\Import3;
use common\models\gallery\GalleryImages;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use backend\models\Helpers;
use backend\modules\shop\models\Import;
use app\modules\shop\models\Offers;

class DefaultController extends Controller
{
    public $layout = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'file') $this->enableCsrfValidation = false;
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = '/main';
        return $this->render('index');
    }

    public function action1cExchange3()
    {
        /*if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth_params = explode(":" , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            $_SERVER['PHP_AUTH_USER'] = $auth_params[0];
            unset($auth_params[0]);
            $_SERVER['PHP_AUTH_PW'] = implode('',$auth_params);
        }*/

        $uploadLog = fopen(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/upload.log', 'a');
        fwrite($uploadLog, date('d.m.Y H:i:s', time()) . " - Start\n");

        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

        if ($_SERVER['PHP_AUTH_USER'] != Yii::$app->params['shop']['phpAuthUser'] || $_SERVER['PHP_AUTH_PW'] != Yii::$app->params['shop']['phpAuthPw']) {
            fwrite($uploadLog, date('d.m.Y H:i:s', time()) . " - Auth failure\n");
            return "failure";
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'checkauth') {
            if (!isset(Yii::$app->request->cookies['Hello1C'])) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'Hello1C',
                    'value' => 'Hello'
                ]));
                return "success\nHello1C\nHello";
            }
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'init') {
            @ unlink(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix.zip');
//            (new Helpers())->removeDirectory(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix');
            $zipFile = fopen(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix.zip', 'w');
            fclose($zipFile);
            return "zip=yes\nfile_limit=" . Yii::$app->params['shop']['fileLimit'];
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {

            if ($postData = file_get_contents("php://input")) {
                $zipFile = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix.zip';
                $unzipDir = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix';

                $uploadZip = fopen($zipFile, 'a+');
                fwrite($uploadZip, $postData);

                $zip = new \ZipArchive();
                $res = $zip->open($zipFile);
                if ($res === true) {
                    $zip->extractTo($unzipDir);
                    $zip->close();
                }

                exec("export LC_ALL=ru_RU.UTF-8 && find " . $unzipDir . "/. -type f -exec sh -c 'np=`echo {} | iconv -f cp1252 -t cp850| iconv -f cp866`; mv \"{}\" \"\$np\"' \;");
            }

            $fileName = Yii::$app->request->get('filename');
            $fullPathXML = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/' . $fileName;

            if (preg_match('/^import_/', $fileName)) {
                fwrite($uploadLog, date("d.m.Y H:i:s") . " Start: " . Yii::$app->request->get('filename') . "\n");
                $model = new Import3();
                $model->parser($fullPathXML, $uploadLog);
                fwrite($uploadLog, date("d.m.Y H:i:s") . " Finish: " . Yii::$app->request->get('filename') . "\n");
            }

            if (preg_match('/^offers_/', $fileName)) {
                fwrite($uploadLog, date("d.m.Y H:i:s") . " Start: " . Yii::$app->request->get('filename') . "\n");
                $model = new Offers3();
                $model->parser($fullPathXML);
                fwrite($uploadLog, date("d.m.Y H:i:s") . " Finish: " . Yii::$app->request->get('filename') . "\n");
            }

            if (preg_match('/^prices_/', $fileName)) {
                fwrite($uploadLog, date("d.m.Y H:i:s") . " Start: " . Yii::$app->request->get('filename') . "\n");
                $model = new Prices3();
                $model->parser($fullPathXML);
                fwrite($uploadLog, date("d.m.Y H:i:s") . " Finish: " . Yii::$app->request->get('filename') . "\n");
            }
        }

        fwrite($uploadLog, date('d.m.Y H:i:s', time()) . " - Finish\n");

        fclose($uploadLog);

        return "success\n";
    }

    public function action1cExchange()
    {
//        $this->layout = false;

        $upload_log = fopen(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/upload.log', 'a');

        if ($_SERVER['PHP_AUTH_USER'] != Yii::$app->params['shop']['phpAuthUser'] || $_SERVER['PHP_AUTH_PW'] != Yii::$app->params['shop']['phpAuthPw']) {
            echo "failure";
            return false;
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'checkauth') {
            fwrite($upload_log, '2. Отправка Hello1C' . "\n");
            if (!isset(Yii::$app->request->cookies['Hello1C'])) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'Hello1C',
                    'value' => 'Hello'
                ]));
                echo "success\nHello1C\nHello";
            }
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'init') {
            @ unlink(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix.zip');
            (new Helpers())->removeDirectory(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix');
            $zip_file = fopen(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix.zip', 'w');
            fclose($zip_file);
            echo "zip=yes\nfile_limit=" . Yii::$app->params['shop']['fileLimit'];
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {
            if ($postdata = file_get_contents("php://input")) {
                $zip_file = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix.zip';
                $unzip_dir = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix';

                (new Helpers())->removeDirectory($unzip_dir);
                mkdir($unzip_dir);

                $upload_zip = fopen($zip_file, 'a+');
                fwrite($upload_zip, $postdata);

                $zip = new \ZipArchive();
                $res = $zip->open($zip_file);
                if ($res === true) {
                    $zip->extractTo($unzip_dir);
                    $zip->close();
                }

                exec("export LC_ALL=ru_RU.UTF-8 && find " . $unzip_dir . "/. -type f -exec sh -c 'np=`echo {} | iconv -f cp1252 -t cp850| iconv -f cp866`; mv \"{}\" \"\$np\"' \;");
//                exec ("convmv -r -f cp866 -t utf-8 --notest {$unzip_dir}");

//                $import = new Import();
//                $import->decodeImageName($unzip_dir);

                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'import.xml') {
                $importXml = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/import.xml';
                if (Yii::$app->request->get('method') && Yii::$app->request->get('method') == 'console') {
                    exec('php ' . Yii::getAlias('@app') . '/../yii shop/import ' . $importXml);
                } else {
                    $import = new Import();
                    $import->parser($importXml);
                }

                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'offers.xml') {
                //exec('php '.Yii::getAlias('@app').'/../yii shop/offers '.Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/offers.xml');
                $offers = new Offers();
                $offers->parser(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/offers.xml');

                echo "success";
            }
        }

        fclose($upload_log);

        return false;
    }

    public function actionHandImport3($xmlFile = false)
    {
        if ($xmlFile) {
            $importXml = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/' . $xmlFile;

            $import3 = new Import3();
            $import3->parser($importXml);
        }
    }

    public function actionHandImport()
    {
        $zipFile = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix.zip';
        $unzipDir = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix';

        $zip = new \ZipArchive();
        $res = $zip->open($zipFile);
        if ($res === true) {
            $zip->extractTo($unzipDir);
            $zip->close();
        }

//        $import_file = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/import.xml';
//
//        $model = new Import();
//        $model->decodeImageName(Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix');
//        $model->parser($import_file);

        return false;
    }

    public function actionHandImportConsole()
    {
        exec('php ' . Yii::getAlias('@app') . '/../yii shop/default/import ' . Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/import.xml');
    }

    public function actionHandOffers3($xmlFile = false)
    {
        if ($xmlFile) {
            $offersXml = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/' . $xmlFile;

            $model = new Offers3();
            $model->parser($offersXml);
        }
    }

    public function actionHandPrices3($xmlFile = false)
    {
        if ($xmlFile) {
            $priceXml = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/' . $xmlFile;
            $model = new Prices3();
            $model->parser($priceXml);
        }
    }

    public function actionHandOffers($offers = 'offers.xml')
    {
        $offers = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/offers.xml';

        $model = new Offers();
        $model->parser($offers);

        return false;
    }

    public function actionDecodeImageName()
    {
        $unzip_dir = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix';

        $import = new Import();
        $import->decodeImageName($unzip_dir);
    }

    public function actionHandContragents3($xmlFile = false)
    {
        if ($xmlFile) {
            $contragentsXml = Yii::getAlias('@app') . Yii::$app->params['shop']['uploadDir'] . '/1cbitrix/' . $xmlFile;
            $model = new Contragents3();
            $model->parser($contragentsXml);
        }
    }

    public function actionConvertImage()
    {
        $model = new GalleryImages();
        $helper = new Helpers();
        $images = GalleryImages::find()->where(['gallery_groups_id' => 579])->all();

        if ($images) {
            /** @var GalleryImages $image */
            foreach ($images as $image) {
                $smallFilePath = Yii::getAlias('@frontend/web').$image->small;
                $largeFilePath = Yii::getAlias('@frontend/web').$image->large;
                $extension = pathinfo($largeFilePath, PATHINFO_EXTENSION);

                $pictureParams = json_decode($image->galleryGroup->galleryType->picture_params);
                $picture = json_decode($image->picture);
                foreach ($pictureParams as $key => $media) {
                    $fullPath = preg_replace('/\/$/', '', Yii::getAlias('@frontend/web').$media->path);
                    $mediaPath = preg_replace('/\/$/', '', $media->path);
                    $helper->makeDirectory($fullPath);

                    if (!isset($picture->$key)) {
                        echo $key.'<br>';

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

    }
}
