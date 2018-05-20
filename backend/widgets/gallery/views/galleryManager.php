<?php
use yii\helpers\Html;
use kartik\sortable\Sortable;
use backend\widgets\gallery\Gallery;

/** @var $group */
/** @var $gallery_groups_id */
/** @var $gallery_images_id */

if ($gallery_groups_id) {
    $images = array();
    if ($group) {
        $images = (new Gallery())->getImages($gallery_groups_id);
    } elseif ($gallery_images_id) {
        $images[0] = (new Gallery())->getImage($gallery_images_id);
    }
    foreach ($images as $image) {
        $items[] = [
            'content' => Html::tag('div', Html::button(null, [
                'class' => 'btn btn-default btn-xs glyphicon glyphicon-remove btn-flat btn-sm',
                'onclick' => 'galleryManager.deleteImage("'.$image['id'].'")',
            ]), [
                'style' => 'background:url("'.$image['small'].'") 50% 50% no-repeat; background-size:cover;',
                'class' => 'upload-image',
                'data-image-id' => $image['id'],
                'id' => 'upload-image-'.$image['id'],
            ]),
        ];
    }
}

$items[] = [
    'content' => '<span class="glyphicon glyphicon-plus"></span><br>Добавить фото' .
        Html::fileInput(null, null, [
            'multiple' => $group,
            'class' => 'image-upload-action',
            'id' => $this->context->id.'-image-upload-action',
            'onchange' => 'galleryManager.prepareUpload($(this))',
        ]),
    'disabled' => true,
    'options' => [
        'onclick' => 'document.getElementById("'.$this->context->id.'-image-upload-action").click()',
    ]
];
?>

<?= Sortable::widget([
    'type' => 'grid',
    'items' => $items,
    'pluginEvents' => [
        'sortupdate' => 'function(e, ui) { galleryManager.sortableImage("'.$this->context->id.'") }',
    ],
    'options' => [
        'class' => 'list-gallery-manager',
    ],
])?>