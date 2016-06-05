<?php

namespace app\modules\ftptransfer\controllers;

use Yii;
use yii\console\Controller;

/**
 * Default controller for the `ftptransfer` module
 */
class DefaultController extends Controller
{
    public $upload_dir = '/uploads/ftptransfer';

    public function actionSynchronization()
    {
        if (date('w', time()) == 0 || date('w', time()) == 6) return;

        $transfer_src = ftp_connect('91.197.79.112');
        $login_result = ftp_login($transfer_src, 'transfer', 'dnUtd74n');
        if (!$login_result) return false;
        ftp_pasv($transfer_src, TRUE);

        $files = ftp_nlist($transfer_src, ".");
        foreach ($files as $file) {
            if (!is_file(Yii::getAlias('@backend/web/').$this->upload_dir.'/'.$file)) {
                $transfer_dst = ftp_connect('37.46.85.148');
                $login_result = ftp_login($transfer_dst, 'carakas', 'hokwEw21');
                if (!$login_result) return false;
                ftp_pasv($transfer_dst, TRUE);

                ftp_get($transfer_src, Yii::getAlias('@backend/web/').$this->upload_dir.'/'.$file, $file, FTP_ASCII);
                ftp_put($transfer_dst, $file, Yii::getAlias('@backend/web/').$this->upload_dir.'/'.$file, FTP_BINARY);

                ftp_close($transfer_dst);
            }
        }

        ftp_close($transfer_src);
    }
}
