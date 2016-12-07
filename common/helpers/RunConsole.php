<?php

namespace common\helpers;


use vova07\console\ConsoleRunner;
use Yii;

class RunConsole extends ConsoleRunner
{
    public function run($cmd)
    {
        $cmd = Yii::getAlias($this->file) . ' ' . $cmd;
        if ($this->isWindows() === true) {
            pclose(popen('start /b ' . $cmd, 'r'));
        } else {
            pclose(popen($cmd . ' /dev/null &', 'r'));
        }
        return true;
    }
}