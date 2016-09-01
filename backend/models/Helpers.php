<?php

namespace app\models;

use yii\base\Model;

class Helpers extends Model
{
    public function removeDirectory($dir)
    {
        if (file_exists($dir)) {
            if ($objs = glob($dir."/*")) {
                foreach($objs as $obj) {
                    is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
                }
            }
            rmdir($dir);
        }
    }

    public function makeDirectory($path)
    {
        $dir = '';
        foreach (preg_split('/\//', $path) as $item) {
            $dir .= $item.'/';
            if (!is_dir($dir)) mkdir($dir);
        }
    }
}