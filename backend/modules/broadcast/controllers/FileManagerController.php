<?php

namespace app\modules\broadcast\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use common\models\broadcast\BroadcastFiles;

/**
 * Default controller for the `multicast` module
 */
class FileManagerController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['file-upload', 'file-delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actionFileUpload()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) Yii::$app->response->format = Response::FORMAT_JSON;

        $mimeTypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/tiff', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $file = $_FILES[$request->post('name')];

        if (in_array(mime_content_type($file['tmp_name']), $mimeTypes)) {
            $tmp_name = $file['tmp_name'];
            $pathParts = pathinfo($file['name']);
            $dir = Yii::getAlias('@backend').'/web'.Yii::$app->params['broadcast']['upload'];

            if (file_exists($dir.'/'.$pathParts['basename'])) unlink($dir.'/'.$pathParts['basename']);

            if (move_uploaded_file($tmp_name, $dir.'/'.$pathParts['basename'])) {
                $broadcastFile = new BroadcastFiles();
                $broadcastFile->name = $file['name'];
                $broadcastFile->file = Yii::getAlias('@web').Yii::$app->params['broadcast']['upload'].'/'.$pathParts['basename'];
                $broadcastFile->broadcast_id = $request->post('broadcast_id');
                $broadcastFile->save();

                return [
                    'initialPreview' => $broadcastFile->file,
                    'initialPreviewConfig' => ['caption' => $broadcastFile->file, 'url' => Yii::getAlias('@web').'/broadcast/file-manager/file-delete', 'extra' => ['id' => $broadcastFile->id]]
                ];
            } else {
                return ['error' => 'Ошибка загрузки файла '.$_FILES['file']['name']];
            }
        } else {
            return ['error' => $_FILES['file']['name'].' неверного формата.'];
        }
    }

    public function actionFileDelete()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) Yii::$app->response->format = Response::FORMAT_JSON;

        $file = BroadcastFiles::findOne($request->post('id'));
        unlink(Yii::getAlias('@backend/web').preg_replace('/^'.addcslashes(Yii::$app->params['broadcast']['clearMngUrl'], '/').'/', '', $file->file));

        BroadcastFiles::findOne($request->post('id'))->delete();

        return ['success' => true];
    }
}
