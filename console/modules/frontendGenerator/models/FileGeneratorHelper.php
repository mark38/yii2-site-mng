<?php

namespace app\modules\frontendGenerator\models;

class FileGeneratorHelper
{
    public function checkDirPresence($dir) {
        return is_dir($dir);
    }

    public function checkFilesPresence() {

    }
}