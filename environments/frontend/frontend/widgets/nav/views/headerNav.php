<?php
/**
 * @var $this \yii\web\View
 * @var $links \common\models\main\Links
 * @var array $breadcrumbsId
 */
use kartik\helpers\Html;
use frontend\widgets\forms\SearchForm;

?>

<div class="cd-morph-dropdown is-top">
    <a href="#0" class="nav-trigger">Каталог<span aria-hidden="true"></span></a>
    <div class="container wrap-main-nav">
        <nav class="main-nav pull-left">
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
                <li class="search-trigger-item"><?=Html::a(null, null, ['class' => 'has-dropdown', 'data-content' => 'search', 'id' => 'search-trigger'])?></li>
            </ul>
        </nav>
    </div>

    <div class="morph-dropdown-wrapper">
        <div class="dropdown-list">
            <div class="container">
                <ul class="list-unstyled">
                    <?php
                    /** @var \common\models\main\Links $link */
                    foreach ($links as $link) {
                        $class = $link->css_class ? $link->css_class : 'links';
                        echo Html::beginTag('li', ['id' => $link->name, 'class' => 'dropdown '.$class]);
                        echo Html::a($link->anchor, $link->url, ['class' => 'label text-left']);

                        if ($link->child_exist) {
                            switch ($link->css_class) {
                                case "promo":
                                    echo $this->render('headerNavPromo', [
                                        'link' => $link,
                                        'childLinks' => $link->activeLinks,
                                    ]);
                                    break;

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
                    <li id="search" class="dropdown">
                        <div class="content"></div>
                    </li>
                </ul>
                <div class="bg-layer" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</div>
