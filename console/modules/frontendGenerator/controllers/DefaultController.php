<?php

namespace app\modules\frontendGenerator\controllers;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Provides checking frontend files and generating whole frontend after INIT
 * Class DefaultController
 * @package app\modules\frontendGenerator\controllers
 */
class DefaultController extends Controller
{
    private $isOk = true;
    private $dirs = [
        'frontend',
        'frontend/config',
        'frontend/web',
        'frontend/runtime', //УСТАНОВИТЬ ПРАВА!!!
        'frontend/runtime/logs',
        'frontend/runtime/cache',
        'frontend/runtime/debug',
        'frontend/controllers',
        'frontend/models',
        'frontend/views/site',
        'frontend/views/layouts',
        'frontend/assets',
        'frontend/web/assets', //УСТАНОВИТЬ ПРАВА!
        'frontend/widgets',
        'frontend/widgets/content',
        'frontend/widgets/content/views',
        'frontend/widgets/nav',
        'frontend/widgets/nav/views',
        'frontend/widgets/nav/assets',
    ];
    private $files = [
        'frontend/config/main.php',
        'frontend/config/main-local.php',
        'frontend/config/params-local.php',
        'frontend/config/bootstrap.php',
        'frontend/web/index.php',
        'frontend/web/.htaccess',
        'frontend/web/robots.txt',
        'frontend/web/.gitignore',
        'frontend/controllers/SiteController.php',
        'frontend/controllers/SitemapController.php',
        'frontend/models/NavLinks.php',
        'frontend/models/Widget.php',
        'frontend/views/site/index.php',
        'frontend/views/site/main.php',
        'frontend/views/layouts/index.php',
        'frontend/views/layouts/main.php',
        'frontend/assets/AppAsset.php',
        'frontend/widgets/content/RenderView.php',
        'frontend/widgets/content/views/header.php',
        'frontend/widgets/content/views/footer.php',
        'frontend/widgets/nav/Top.php',
        'frontend/widgets/nav/Footer.php',
        'frontend/widgets/nav/NavAsset.php',
        'frontend/widgets/nav/views/topNav.php',
        'frontend/widgets/nav/views/headerNav.php',
        'frontend/widgets/nav/views/footerNav.php',
        'frontend/widgets/nav/Header.php',
        'frontend/web/css/site.css',
        'frontend/web/js/wow.min.js',
        'frontend/web/js/site.js',
        'frontend/widgets/nav/assets/nav.css',
        'vendor/iutbay/yii2-kcfinder/KCFinder.php'
    ];

    /**
     * Generate whole new frontend
     * @return string
     */
    public function actionGenerate() {
        return 'Frontend generated successfully';
    }


    public function actionCheckWholeFrontend() {
        $this->actionCheckDirs();
        $this->actionCheckFiles();
        if ($this->isOk) {
            $this->stdout('The whole frontend is OK' . PHP_EOL, Console::FG_GREEN);
        } else {
            $this->stdout('Frontend is FAILED' . PHP_EOL, Console::FG_RED);
        }
    }

    /**
     * Check frontend directories
     * @return string
     */
    public function actionCheckDirs() {
        foreach ($this->dirs as $dir) {
            if (is_dir($dir)) {
                $this->stdout('Directory - ' . $dir . ' - OK'  . PHP_EOL, Console::FG_GREEN);
            } else {
                $this->stdout('Directory - ' . $dir . ' - FAIL'  . PHP_EOL, Console::FG_RED);
                $this->isOk = false;
            }
        }

    }

    /**
     * Check frontend files
     * @return string
     */
    public function actionCheckFiles() {
        foreach ($this->files as $file) {
            if (file_exists($file)) {
                $this->stdout('File - ' . $file . ' - OK'  . PHP_EOL, Console::FG_GREEN);
            } else {
                $this->stdout('File - ' . $file . ' - FAIL'  . PHP_EOL, Console::FG_RED);
                $this->isOk = false;
            }
        }

    }
}
