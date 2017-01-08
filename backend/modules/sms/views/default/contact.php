<?php
use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $contact \common\models\sms\SmsContacts */
/** @var $num */

?>

<tr id="sms-contact-<?=$contact->id?>">
    <td><?=$num?></td>
    <td>+<?=$contact->phone?></td>
    <td><?=$contact->surname.' '.$contact->name.' '.$contact->patronymic?></td>
    <td>
        <?php
        if (!$contact->female && !$contact->male) {
            echo 'Не указано';
        } elseif ($contact->male) {
            echo 'Мужской';
        } else {
            echo 'Женский';
        }
        ?>
    </td>
    <td><?= $contact->state ? '+' : '-' ?></td>
    <td class="text-right">
        <?=Html::button('<i class="fa fa-edit" aria-hidden="true"></i>', [
            'class' => 'btn btn-xs btn-link',
            'onclick' => 'formContact("ch","'.$contact->id.'")',
        ])?>
        <?=Html::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', [
            'class' => 'btn btn-xs btn-link',
            'onclick' => 'formContact("rem","'.$contact->id.'")',
        ])?>
    </td>
</tr>
