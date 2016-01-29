<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\shop\Import;
use backend\models\shop\Offers;

/**
 * Site controller
 */
class ShopController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['test', 'login', 'error', '1c-exchange', 'hand-import', 'hand-offers'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    '1c-exchange' => ['post', 'get'],
                ],
            ],
        ];
    }

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

    public function actionTest()
    {
        exec( "sh ".Yii::getAlias('@app').'/web/files/convmv.sh > '.Yii::getAlias('@app').'/web/files/convmv.log' );
    }

    public function action1cExchange()
    {
        if ( $_SERVER['PHP_AUTH_USER'] != Yii::$app->params['phpAuthUser'] || $_SERVER['PHP_AUTH_PW'] != Yii::$app->params['phpAuthPw'] ) {
            echo "failure";
            return false;
        }

        if (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'checkauth') {
            if (!isset(Yii::$app->request->cookies['Hello1C'])) {
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'Hello1C',
                    'value' => 'Hello'
                ]));
                echo "success\nHello1C".Yii::$app->request->cookies['Hello1C'];
            }
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('mode') == 'init') {
            echo "zip=yes\nfile_limit=314572800";
        } elseif (Yii::$app->request->get('type') == 'catalog' && Yii::$app->request->get('filename')) {
            if ( $postdata = file_get_contents( "php://input" ) ) {
                $zip_file = Yii::getAlias('@app').'/web/files/1cbitrix.zip';
                $unzip_dir = Yii::getAlias('@app').'/web/files/1cbitrix';
                    exec( 'rm -r '.$unzip_dir );
                    mkdir( $unzip_dir );
                $upload_zip = fopen( $zip_file, 'w' );
                fwrite($upload_zip, $postdata);
                fclose( $upload_zip );
                $unzip_command = exec( '/usr/bin/which unzip' );

                //exec( "{$unzip_command} {$zip_file} -d {$unzip_dir}" );
                exec( "export LC_ALL=ru_RU.UTF-8 && {$unzip_command} {$zip_file} -d {$unzip_dir}" );

                //exec( "sh ".Yii::getAlias('@app').'/web/files/convmv.sh' );
                //exec ("export LC_ALL=ru_RU.UTF-8 && find ".$unzip_dir."/. -type f -exec sh -c 'np=`echo {} | iconv -f cp1252 -t cp850| iconv -f cp866`; mv \"{}\" \"\$np\"' \;");

                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'import.xml') {
                exec('php '.Yii::getAlias('@backend').'/../yii shop/import '.Yii::getAlias('@backend').'/web/files/1cbitrix/import.xml');
                echo "success";
            } elseif (Yii::$app->request->get('filename') == 'offers.xml') {
                exec('php '.Yii::getAlias('@backend').'/../yii shop/offers '.Yii::getAlias('@backend').'/web/files/1cbitrix/offers.xml');
                echo "success";
            }
        }

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