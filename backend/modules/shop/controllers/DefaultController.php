<?php

namespace app\modules\shop\controllers;

use backend\modules\shop\models\Import3;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use app\models\Helpers;
use backend\modules\shop\models\Import;
use app\modules\shop\models\Offers;

class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'file')  $this->enableCsrfValidation = false;
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function action1cExchange3()
    {
        $this->layout = false;

        $upload_log = fopen(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/upload.log', 'a');

        if ( $_SERVER['PHP_AUTH_USER'] != Yii::$app->params['shop']['phpAuthUser'] || $_SERVER['PHP_AUTH_PW'] != Yii::$app->params['shop']['phpAuthPw'] ) {
            fwrite($upload_log, "Failure\n");
            echo "failure";
            return false;
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'checkauth') {
            fwrite($upload_log, date("d.m.Y H:i:s")." Ответ: success - Hello1C - Hello\n");

            if (!isset(Yii::$app->request->cookies['Hello1C'])) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'Hello1C',
                    'value' => 'Hello'
                ]));
                echo "success\nHello1C\nHello";
            }
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'init') {
            fwrite($upload_log, date("d.m.Y H:i:s")." Загрузка архива. Ответ: zip=yes; file_limit=".Yii::$app->params['shop']['fileLimit']."\n");

            @ unlink(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix.zip');
            (new Helpers())->removeDirectory(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix');
            $zip_file = fopen(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix.zip', 'w');
            fclose($zip_file);
            echo "zip=yes\nfile_limit=".Yii::$app->params['shop']['fileLimit'];
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {
            if ( $postdata = file_get_contents( "php://input" ) ) {
                fwrite($upload_log, date("d.m.Y H:i:s")." Распаковка. Ответ: success\n");

                $zip_file = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix.zip';
                $unzip_dir = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix';

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

                exec ("export LC_ALL=ru_RU.UTF-8 && find ".$unzip_dir."/. -type f -exec sh -c 'np=`echo {} | iconv -f cp1252 -t cp850| iconv -f cp866`; mv \"{}\" \"\$np\"' \;");

                echo "success";
            } elseif ( !empty(Yii::$app->request->get('filename')) ) {
                if (preg_match('/import___/', Yii::$app->request->get('filename'))) {
                    fwrite($upload_log, date("d.m.Y H:i:s")." import filename: ".Yii::$app->request->get('filename')."\n");

                    $importXml = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/'.Yii::$app->request->get('filename');
//                    if (!copy($importXml, Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/'.Yii::$app->request->get('filename'))) {
//                        fwrite($upload_log, date("d.m.Y H:i:s")." Не удалось скопировать файл ".Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/'.Yii::$app->request->get('filename')."\n");
//                    }

                    if (Yii::$app->request->get('method') && Yii::$app->request->get('method') == 'console') {
                        exec('php '.Yii::getAlias('@app').'/../yii shop/import '.$importXml);
                    } else {
                        $import3 = new Import3();
                        $import3->parser($importXml, $upload_log);
                    }

                    echo "success";
                } elseif (preg_match('/offers___/', Yii::$app->request->get('filename'))) {
                    fwrite($upload_log, date("d.m.Y H:i:s")." offers filename: ".Yii::$app->request->get('filename')."\n");

                    echo "success";
                } elseif (preg_match('/offers___/', Yii::$app->request->get('filename'))) {
                    fwrite($upload_log, date("d.m.Y H:i:s")." offers filename: ".Yii::$app->request->get('filename')."\n");

                    echo "success";
                } elseif (preg_match('/prices___/', Yii::$app->request->get('filename'))) {
                    fwrite($upload_log, date("d.m.Y H:i:s")." prices filename: ".Yii::$app->request->get('filename')."\n");

                    echo "success";
                } else {
                    fwrite($upload_log, date("d.m.Y H:i:s")." unknown filename: ".Yii::$app->request->get('filename')."\n");

                    echo "success";
                }
            }
        }
    }

    public function action1cExchange()
    {
        $this->layout = false;

        $upload_log = fopen(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/upload.log', 'a');

        if ( $_SERVER['PHP_AUTH_USER'] != Yii::$app->params['shop']['phpAuthUser'] || $_SERVER['PHP_AUTH_PW'] != Yii::$app->params['shop']['phpAuthPw'] ) {
            echo "failure";
            return false;
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'checkauth') {
            fwrite($upload_log, '2. Отправка Hello1C'."\n");
            if (!isset(Yii::$app->request->cookies['Hello1C'])) {
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'Hello1C',
                    'value' => 'Hello'
                ]));
                echo "success\nHello1C\nHello";
            }
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'init') {
            @ unlink(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix.zip');
            (new Helpers())->removeDirectory(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix');
            $zip_file = fopen(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix.zip', 'w');
            fclose($zip_file);
            echo "zip=yes\nfile_limit=".Yii::$app->params['shop']['fileLimit'];
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {
            if ( $postdata = file_get_contents( "php://input" ) ) {
                $zip_file = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix.zip';
                $unzip_dir = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix';

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

                exec ("export LC_ALL=ru_RU.UTF-8 && find ".$unzip_dir."/. -type f -exec sh -c 'np=`echo {} | iconv -f cp1252 -t cp850| iconv -f cp866`; mv \"{}\" \"\$np\"' \;");
//                exec ("convmv -r -f cp866 -t utf-8 --notest {$unzip_dir}");

//                $import = new Import();
//                $import->decodeImageName($unzip_dir);

                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'import.xml') {
                $importXml = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/import.xml';
                if (Yii::$app->request->get('method') && Yii::$app->request->get('method') == 'console') {
                    exec('php '.Yii::getAlias('@app').'/../yii shop/import '.$importXml);
                } else {
                    $import = new Import();
                    $import->parser($importXml);
                }

                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'offers.xml') {
                //exec('php '.Yii::getAlias('@app').'/../yii shop/offers '.Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/offers.xml');
                $offers = new Offers();
                $offers->parser(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/offers.xml');

                echo "success";
            }
        }

        fclose($upload_log);

        return false;
    }

    public function actionHandImport3($xmlFile=false)
    {
        if ($xmlFile) {
            $importXml = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/'.$xmlFile;

            $import3 = new Import3();
            $import3->parser($importXml);
        }
    }

    public function actionHandImport()
    {
        $this->layout = false;

        $import_file = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/import.xml';

        $model = new Import();
        $model->decodeImageName(Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix');
        $model->parser($import_file);

        return false;
    }

    public function actionHandImportConsole()
    {
        exec('php '.Yii::getAlias('@app').'/../yii shop/default/import '.Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/import.xml');
    }

    public function actionHandOffers($offers='offers.xml')
    {
        $offers = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix/offers.xml';

        $model = new Offers();
        $model->parser($offers);

        return false;
    }

    public function actionDecodeImageName()
    {
        $unzip_dir = Yii::getAlias('@app').Yii::$app->params['shop']['uploadDir'].'/1cbitrix';

        $import = new Import();
        $import->decodeImageName($unzip_dir);
    }
}
