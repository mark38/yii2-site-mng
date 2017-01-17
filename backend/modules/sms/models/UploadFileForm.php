<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadFileForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $uploadFile;

    public function rules()
    {
        return [
            [['uploadFile'], 'file'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->uploadFile->saveAs('uploads/sms/' . $this->uploadFile->baseName . '.' . $this->uploadFile->extension);
            return true;
        } else {
            return false;
        }
    }
}