<?php
use Cyantree\Grout\App\Generators\Template\TemplateContext;
use Grout\BootstrapModule\GlobalFactory;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleResult;

/** @var $this TemplateContext */

$g = GlobalFactory::get($this->app);
$q = $g->appQuick();

/** @var WebConsoleResult $result */
$result = $this->in->get('result');
?>
<!doctype html>
<html>
<head>
    <title>WebConsole</title>
    <meta charset="UTF-8" />
</head>
<body>
<form action="<?=$q->e($this->task->url)?>" method="post">
    <input type="text" name="command" maxlength="255" size="150" style="font-family: Consola, Courier New, Courier" value="<?=$q->e($this->in->get('command'))?>" /><br />
    <input type="submit" name="execute" /><br />
    <textarea name="result" cols="150" rows="10" readonly="readonly"><?=$q->e($result->result)?></textarea>
    <?php
    foreach($result->data->getData() as $key => $value) {
        echo '<input type="hidden" name="' . $q->e($key) . '" value="' . $q->e($value) . '" />';
    }
    ?>
</form>
<?php
if ($result->redirectToCommand) {
    ?>
    <script>
        var form = document.getElementsByTagName('form')[0];
        var command = document.getElementsByName('command')[0];

        setTimeout(function() {
            command.value = "<?= $q->e($result->redirectToCommand, 'js') ?>";
            form.submit();
        }, <?= $result->redirectToCommandDelay * 1000 ?>);
    </script>
<?php
}
?>
</body>
</html>