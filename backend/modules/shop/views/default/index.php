<div class="shop-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>

<?php

$dir = Yii::getAlias('@backend/web'.$dir);
//convmv -f cp1252 -t cp850 * --notest  && convmv -f cp866 -t utf-8 * --notest
foreach (scandir($dir) as $key => $value) {
    if (!in_array($value, array('.', '..'))) {
        echo $value."<br>";
        $new_name = iconv("CP866", "UTF-8", $value);
        if ($new_name != $value) {
            echo $new_name.' != '.$value;
            rename($dir.'/'.$value, $dir.'/'.$new_name);
        }
        echo '<hr>';
    }
}

?>