<?php

namespace app\modules\broadcast\models;

use Yii;
use common\models\broadcast\Broadcast;
use common\models\broadcast\BroadcastFiles;

class BroadcastForm extends Broadcast
{
    public $initialPreviewConfig = false;

    public function init()
    {
        if (Yii::$app->request->get('id')) {
            if (!Yii::$app->request->isPost) {
                $files = BroadcastFiles::find()->where(['broadcast_id' => Yii::$app->request->get('id')])->all();
                if ($files) {
                    foreach ($files as $file) {
                        $this->initialPreviewConfig[] = [
                            'caption' => $file->name,
                            'url' => Yii::getAlias('@web').'/broadcast/file-manager/file-delete',
                            'key' => $file->id,
                            'file' => $file->file,
                            'extra' => ['id' => $file->id],
                        ];
                    }
                }
            }
        }
    }
}