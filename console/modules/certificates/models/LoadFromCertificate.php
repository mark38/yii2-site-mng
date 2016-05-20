<?php
namespace console\modules\certificates\models;

use Yii;
use yii\base\Model;
use InvalidArgumentException;
use yii\helpers\ArrayHelper;



class LoadFromCertificate extends Model
{
    public function loadFromFile($file)
    {
        $dir = Yii::getAlias('@backend/web/uploads/temp_txt');
        if (mime_content_type($dir.'/'.$file) == 'text/plain') {
            $this->parseFile($dir.'/'.$file);
        }
    }

    private function parseFile($file)
    {
        $txt_file = file_get_contents($file);
        $utf8_file = iconv("windows-1251", "UTF-8", $txt_file);
        $certificates = preg_split( "/(\n|\r\n){2,}/", $utf8_file);
        print_r($certificates);
        foreach ($certificates as $certificate) {
            if ($certificate) {
                $this->saveCertificate($certificate);
            }
        }
        unlink($file);
    }

    private function saveCertificate($certificate)
    {
        $dir = Yii::getAlias('@backend/web/uploads/certificates_txt/');
        $certificate_number = '';
        $wagon_number = '';

        preg_match('/(ИВЦ ЖА)\s+(\D+)\s+(\d+)/', $certificate, $match);
        if (isset($match[3])) {
            $certificate_number = $match[3];
        }
        preg_match('/(Вагон)\s+(\d+)(.+)/', $certificate, $match);
        if (isset($match[2])) {
            $wagon_number = $match[2];
        }
        $new_file = fopen($dir.$certificate_number.'_'.$wagon_number.".txt", "w") or die("Unable to open file!");
        fwrite($new_file, $certificate);
        fclose($new_file);
    }
}