<?php
use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $news_list \common\models\news\News */
/** @var $news \common\models\news\News */
/** @var $link \common\models\main\Links */
/** @var $prev_news \common\models\main\Contents */
/** @var $full_news \common\models\main\Contents */

$this->title = 'Новости сайта';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-sm-5">

        <div class="box box-default">
            <div class="box-header with-border">

                <?= Html::a('<i class="fa fa-plus"></i> Добавить новость', ['', 'news' => 'add', 'news_types_id' => 1])?>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">

            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>

    <?php if (Yii::$app->request->get('news')) {?>
        <div class="col-sm-7">
            <?= $this->render('newsForm', [
                'news' => $news,
                'link' => $link,
                'prev_news' => $prev_news,
                'full_news' => $full_news,
            ])?>
        </div>
    <?php }?>

</div>
