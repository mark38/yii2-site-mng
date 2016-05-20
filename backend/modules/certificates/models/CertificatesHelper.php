<?php

namespace app\modules\certificates\models;
use common\models\certificates\FilePaths;
use common\models\certificates\RequestedCertificates;
use common\models\certificates\Requests;
use common\models\certificates\Tasks;
use Faker\Provider\File;
use yii\base\Model;
use ZipArchive;


/**
 * certificate module definition class
 */
class CertificatesHelper extends Model
{
    private $tasks_id;

    public function createExcels($tasks_id)
    {
        $this->tasks_id = $tasks_id;
        $requests_array = [];
        $requests = Requests::find()->where(['tasks_id' => $tasks_id])->all();
        foreach ($requests as $request) {
            $requested_certificates = RequestedCertificates::find()->where(['requests_id' => $request->id])->all();
            foreach ($requested_certificates as $requested_certificate) {
                $wagons = $this->parseWagons($requested_certificate->wagons);
                foreach ($wagons as $wagon) {
                    $requests_array[$requested_certificate->certificate->code][] = $wagon;
                }
            }
        }
        $dir = 'uploads/certificates/task_'.$tasks_id.'_'.date('d.m.Y');

        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        foreach ($requests_array as $certificate => $wagons) {
            $this->writeOneCertificate($wagons, $certificate, $dir);
        }

        $this->createTaskZip($tasks_id);
    }

    private function parseWagons($string)
    {
        $wagons = preg_split('/[\s,]+/', $string);
        return $wagons;
    }

    private function writeOneCertificate($wagons, $certificate, $dir)
    {
        $amount_wagons_in_file = 15;
        $file_number = 0;
        $writed_wagons = 0;

        $wagons = array_values(array_unique($wagons, SORT_NUMERIC));

        while (count($wagons) - $writed_wagons > 0) {
            $file = new \PHPExcel();
            $sheet = $file->getSheet(0);
            for ($i = 1; $i <= $amount_wagons_in_file && count($wagons) - $writed_wagons > 0; $i++){
                $sheet->setCellValue('a'.$i, $wagons[$writed_wagons]);
                $writed_wagons++;
            }
            $file_name = $certificate.'_'.$file_number.'.xlsx';
            $excel_path = $dir.'/'.$file_name;
            $writer = \PHPExcel_IOFactory::createWriter($file, 'Excel2007');
            $writer->save($excel_path);
            $this->writeFilePath($excel_path, $file_name, 'excel', $this->tasks_id);
            $file_number++;
        }
    }

    private function writeFilePath($excel_path, $file_name, $type, $tasks_id)
    {
        if (!FilePaths::find()->where(['name' => $file_name, 'path' => $excel_path, 'type' => $type])->one()) {
            $file_path = new FilePaths();
            $file_path->name = $file_name;
            $file_path->path = $excel_path;
            $file_path->tasks_id = $tasks_id;
            $file_path->type = $type;
            $file_path->save();
        }
    }

    public function createTaskZip($tasks_id)
    {
        $task = Tasks::findOne($tasks_id);
        $name = date('d-m-Y_H-i', $task->created_at).'.zip';
        $file_paths = FilePaths::find()->where(['tasks_id' => $tasks_id, 'type' => 'excel'])->all();
        $zip = new ZipArchive();
        $destination = 'uploads/zip/'.$name;
        if($zip->open($destination, ZipArchive::CREATE) !== true) {
            return false;
        }
        foreach($file_paths as $path)
        {
            $zip->addFile($path->path, $path->name);
        }
        $zip->close();
        $this->writeFilePath($destination, $name, 'excel-zip', $tasks_id);
    }

    public function createCompaniesZip($tasks_id)
    {
        $task = Tasks::findOne($tasks_id);
        foreach ($task->requests as $request) {
            $name = $request->company->name.'_'.$tasks_id.'.zip';
            $zip = new ZipArchive();
            $destination = 'uploads/companies_zip/'.$name;
            if($zip->open($destination, ZipArchive::CREATE) !== true) {
                return false;
            }
            foreach ($request->requestedCertificates as $requested_certificate) {

                $wagons = preg_split('/[\s,]+/', $requested_certificate->wagons);
                foreach($wagons as $wagon)
                {
                    $zip->addFile('uploads/certificates_txt/'.$requested_certificate->certificate->code.'_'.$wagon.'.txt', $requested_certificate->certificate->code.'_'.$wagon.'.txt');
                }


            }
            $zip->close();
            $this->writeFilePath($destination, $name, 'companies-zip', $tasks_id);
        }

    }

    public static function moveFile($file, $folder) {
        return move_uploaded_file( $file['file']['tmp_name'], 'uploads/'.$folder.'/'.$file['file']['name']);
    }

}
