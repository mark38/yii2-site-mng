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
        $divider = '';
        $certificate_number = '';
        foreach ($rows as $row) {
            if ($row) {
                preg_match('/(\D+)\s+(\d+)/', $row, $match);
                $divider = $match[1];
                $certificate_number = $match[2];
                break;
            } else {
                continue;
            }
        }
        foreach ($rows as $row) {
            if (preg_match('/'.$divider.'/', $row)) {
                if ($str) {
                    $this->saveCertificate($str, $certificate_number);
                }
                $str = '';
            }
            $str .= $row."\n";
        }
        $this->saveCertificate($str, $certificate_number);
        unlink($file);
    }

    private function saveCertificate($certificate, $certificate_number)
    {
        $dir = Yii::getAlias('@backend/web/uploads/certificates/certificates_txt/');
        $wagon_number = '';

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