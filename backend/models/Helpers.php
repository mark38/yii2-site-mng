<?php

namespace app\models;

use yii\base\Model;

class Helpers extends Model
{
    public function removeDirectory($dir)
    {
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }
}