<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public $upload_dir = '/web/upload/shop';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function action1cExchange()
    {
        $upload_log = fopen(Yii::getAlias('@app').$this->upload_dir.'/upload.log', 'w+');

        if ( $_SERVER['PHP_AUTH_USER'] != Yii::$app->params['shop']['phpAuthUser'] || $_SERVER['PHP_AUTH_PW'] != Yii::$app->params['shop']['phpAuthPw'] ) {
            echo "failure";
            return false;
        }

        fwrite($upload_log, '1. Авторизация пройдена'."\n");

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'checkauth') {
            fwrite($upload_log, '2. Отправка Hello1C'."\n");
            if (!isset(Yii::$app->request->cookies['Hello1C'])) {
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'Hello1C',
                    'value' => 'Hello'
                ]));
                echo "success\nHello1C".Yii::$app->request->cookies['Hello1C'];
            }
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'init') {
            echo "zip=yes\nfile_limit=314572800";
            fwrite($upload_log, '3. Определение file_limit'."\n");
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {
            fwrite($upload_log, '4. Начало загрузки файлов'."\n");
            if ( $postdata = file_get_contents( "php://input" ) ) {
                $zip_file = Yii::getAlias('@app').$this->upload_dir.'/1cbitrix.zip';
                $unzip_dir = Yii::getAlias('@app').$this->upload_dir.'/1cbitrix';
                exec( 'rm -r '.$unzip_dir );
                mkdir( $unzip_dir );
                $upload_zip = fopen( $zip_file, 'w' );
                fwrite($upload_zip, $postdata);
                fclose( $upload_zip );
                $unzip_command = exec( '/usr/bin/which unzip' );
                exec( "{$unzip_command} {$zip_file} -d {$unzip_dir}" );
                //exec( "export LC_ALL=ru_RU.UTF-8 && {$unzip_command} {$zip_file} -d {$unzip_dir}" );
                //exec( "sh ".Yii::getAlias('@app').'/web/files/convmv.sh' );
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
}
