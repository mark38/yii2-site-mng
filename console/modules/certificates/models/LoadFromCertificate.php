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
        $dir = Yii::getAlias('@backend/web/uploads/certificates/temp_txt');
        if (mime_content_type($dir.'/'.$file) == 'text/plain') {
            $this->parseFile($dir.'/'.$file);
        }
    }

    private function parseFile($file)
    {
        $txt_file = file_get_contents($file);
        $utf8_file = iconv("windows-1251", "UTF-8", $txt_file);
        $rows = explode(PHP_EOL, $utf8_file);
        $str = '';
        foreach ($rows as $row) {
            if (preg_match('/И(В|B)Ц Ж(A|А)/', $row)) {
                if ($str) {
                    $this->saveCertificate($str);
                }
                $str = '';
            }
            $str .= $row."\n";
        }
        $this->saveCertificate($str);
        unlink($file);
    }

    private function saveCertificate($certificate)
    {
        $dir = Yii::getAlias('@backend/web/uploads/certificates/certificates_txt/');
        $certificate_number = '';
        $wagon_number = '';

        if (preg_match('/И(В|B)Ц Ж(A|А)\s+(\D+)\s+(\d+).+/', $certificate, $match)) {
            if (isset($match[4])) {
                $certificate_number = $match[4];
            }
        }

        if (preg_match('/Г(В|B)Ц (М|M)П(С|C)\s+(\D+)\s+(\d+).+/', $certificate, $match)) {
            if (isset($match[5])) {
                $certificate_number = $match[5];
            }
        }

        if (preg_match('/(\d{8})/', $certificate, $match)) {
            if (isset($match[1])) {
                $wagon_number = $match[1];
            }

        }

        $new_file = fopen($dir.$certificate_number.'_'.$wagon_number.".txt", "w") or die("Unable to open file!");
        fwrite($new_file, $certificate);
        fclose($new_file);
    }
}