<?php
/**
 * @var $this \yii\web\View
 * @var $links \common\models\main\Links
 * @var array $breadcrumbsId
 */
use kartik\helpers\Html;
use frontend\widgets\forms\SearchForm;

?>

<header class="cd-morph-dropdown">
    <a href="#0" class="nav-trigger">Каталог<span aria-hidden="true"></span></a>
        <nav class="main-nav">
            <ul>
                <?php
                $dropDownExist = false;
                /** @var \common\models\main\Links $link */
                foreach ($links as $link) {
                    if ($link->child_exist) {
                        $dropDownExist = true;
                        $class = 'has-dropdown';
                    } else {
                        $class = '';
                    }

                    if (isset($breadcrumbsId[$link->id])) $class .= $class ? ' s-active' : 's-active';

                    echo Html::beginTag('li', [
                        'class' => $class,
                        'data-content' => $link->name
                    ]);

                    echo Html::a('<span>'.$link->anchor.'</span>', $link->url);

                    echo Html::endTag('li');
                }
                ?>
            </ul>
        </nav>

    <div class="morph-dropdown-wrapper">
        <div class="dropdown-list">
            <ul>
                <?php
                /** @var \common\models\main\Links $link */
                foreach ($links as $link) {
                    $class = $link->css_class ? $link->css_class : 'links';
                    echo Html::beginTag('li', ['id' => $link->name, 'class' => 'dropdown '.$class]);
                    echo Html::a($link->anchor, $link->url, ['class' => 'label']);

                    if ($link->child_exist) {
                        switch ($link->css_class) {
                            default:
                                echo $this->render('headerNavDefault', [
                                    'link' => $link,
                                    'childLinks' => $link->activeLinks,
                                ]);
                        }
                    }
                    echo Html::endTag('li');
                }
                ?>
            </ul>
            <div class="bg-layer" aria-hidden="true"></div>
        </div>
    </div>
</header>
