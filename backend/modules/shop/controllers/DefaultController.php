<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Cookie;

class DefaultController extends Controller
{
    public $upload_dir = '/web/upload/shop';

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

        $upload_log = fopen(Yii::getAlias('@app').$this->upload_dir.'/upload.log', 'a');
        fwrite($upload_log, '0. Начало загрузки'."\n");

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
            echo "zip=yes\nfile_limit=314572800";
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {
            if ( $postdata = file_get_contents( "php://input" ) ) {
                $zip_file = Yii::getAlias('@app').$this->upload_dir.'/1cbitrix.zip';
                $unzip_dir = Yii::getAlias('@app').$this->upload_dir.'/1cbitrix';

                $upload_zip = fopen( $zip_file, 'w' );
                fwrite($upload_zip, $postdata);

                $zip = new \ZipArchive();
                $res = $zip->open($zip_file);
                if ($res === true) {
                    $zip->extractTo($unzip_dir);
                    $zip->close();
                }
                exec ("export LC_ALL=ru_RU.UTF-8 && find ".$unzip_dir."/. -type f -exec sh -c 'np=`echo {} | iconv -f cp1252 -t cp850| iconv -f cp866`; mv \"{}\" \"\$np\"' \;");
                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'import.xml') {
                //exec('php '.Yii::getAlias('@app').'/../yii shop/import '.Yii::getAlias('@app').$this->upload_dir.'/1cbitrix/import.xml');
                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'offers.xml') {
                //exec('php '.Yii::getAlias('@app').'/../yii shop/offers '.Yii::getAlias('@app').$this->upload_dir.'/1cbitrix/offers.xml');
                echo "success";
            }
        }

        fclose($upload_log);

        return false;
    }

    public function actionHandImport($import='import.xml')
    {
        $import_file = Yii::getAlias('@app').'/web/files/1cbitrix/'.$import;

        $model = new Import();
        $model->parser($import_file);

        return false;
    }

    public function actionHandOffers($offers='offers.xml')
    {
        $offers = Yii::getAlias('@app').'/web/files/1cbitrix/'.$offers;

        $model = new Offers();
        $model->parser($offers);

        return false;
    }
}
