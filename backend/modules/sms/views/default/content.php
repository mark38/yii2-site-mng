<?php
use yii\bootstrap\Html;

/**
 * @var $content \common\models\sms\SmsContent
 * @var $num
 */

?>

<tr>
    <td><?=$num?></td>
    <td>
        <?=Html::tag('strong', $content->comment)?>
        <p><?=$content->content?></p>
    </td>
    <td><?=count($content->smsSends)?></td>
    <td class="text-right">
        <?=Html::a('<i class="fa fa-edit" aria-hidden="true"></i>', ['content', 'id' => $content->id], [
            'class' => 'btn btn-xs btn-link',
        ])?>
    </td>
</tr>


