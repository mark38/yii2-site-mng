<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use app\models\Helpers;
use app\modules\shop\models\Import;
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

    public function action1cExchange()
    {
        $this->layout = false;

        $upload_log = fopen(Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/upload.log', 'a');

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
            unlink(Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix.zip');
            (new Helpers())->removeDirectory(Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix');
            $zip_file = fopen(Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix.zip', 'w');
            fclose($zip_file);
            echo "zip=yes\nfile_limit=".Yii::$app->params['shop']['fileLimit'];
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {
            if ( $postdata = file_get_contents( "php://input" ) ) {
                $zip_file = Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix.zip';
                $unzip_dir = Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix';

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

                //exec ("export LC_ALL=ru_RU.UTF-8 && find ".$unzip_dir."/. -type f -exec sh -c 'np=`echo {} | iconv -f cp1252 -t cp850| iconv -f cp866`; mv \"{}\" \"\$np\"' \;");
                //exec ("convmv -r -f cp866 -t utf-8 --notest {$unzip_dir}");

                $import = new Import();
                $import->decodeImageName($unzip_dir);

                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'import.xml') {
                //exec('php '.Yii::getAlias('@app').'/../yii shop/import '.Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix/import.xml');
                $import = new Import();
                $import->parser(Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix/import.xml');

                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'offers.xml') {
                //exec('php '.Yii::getAlias('@app').'/../yii shop/offers '.Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix/offers.xml');
                $offers = new Offers();
                $offers->parser(Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix/offers.xml');

                echo "success";
            }
        }

        fclose($upload_log);

        return false;
    }

    public function actionHandImport()
    {
        $this->layout = false;

        $import_file = Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix/import.xml';

        $model = new Import();
        $model->parser($import_file);

        return false;
    }

    public function actionHandOffers($offers='offers.xml')
    {
        $offers = Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix/offers.xml';

        $model = new Offers();
        $model->parser($offers);

        return false;
    }

    public function actionDecodeImageName()
    {
        $unzip_dir = Yii::getAlias('@app').Yii::$app->params['shop']['upload_dir'].'/1cbitrix';

        $import = new Import();
        $import->decodeImageName($unzip_dir);
    }
}
