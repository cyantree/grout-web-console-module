<?php
use Grout\AppModule\Types\AppTemplateContext;
use Grout\Cyantree\WebConsoleModule\Pages\WebConsolePage;

/** @var $this AppTemplateContext */

/** @var WebConsolePage $page */
$page = $this->task->page;

$f = $this->factory();
$q = $f->quick();
?>
<!doctype html>
<html>
<head>
    <title>WebConsole</title>
    <base href="<?= $q->e($this->app->url) ?>" />
    <meta charset="UTF-8" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <style>
        input[name=command], div.output {
            font-family: Consolas, "Courier New", Courier, monospace;
        }
        div.title {
            text-decoration: underline;
            font-weight: bold;
            margin-top: 10px;
        }
        div.success {
            color: #00aa00;
        }

        div.info {
            color: black;
        }

        div.warning {
            color: #ff8800;
        }

        div.error {
            color: #ff0000;
        }
    </style>
    <script>
        $(document).ready(function() {
            var $form = $('form');
            var $command = $('input[name=command]');
            var $output = $('div.output');
            var $status = $('div.statusLoading');
            var commandUrl = '<?= $q->e($this->task->module->getRouteUrl('console'), 'js') ?>';

            function stringifyDate(date)
            {
                if (!date) {
                    date = new Date();
                }

                return date.getFullYear() + "-" + (date.getMonth() < 9 ? "0" : "") + (date.getMonth() + 1) + "-" + (date.getDate() <= 9 ? "0" : "") + date.getDate() +
                      " " + (date.getHours() <= 9 ? "0" : "") + date.getHours() + ":" + (date.getMinutes() <= 9 ? "0" : "") + date.getMinutes() + ":" +
                      (date.getSeconds() <= 9 ? "0" : "") + date.getSeconds();
            }

            function processCommandResponse(command, response)
            {
                var $d = $('<div />');
                var $headline = $('<div class="title" />');
                var $commandLink = $('<a />');
                $commandLink.prop('href', 'javascript:submitCommand("' + command.replace(/\\/g, '\\\\').replace(/"/g, '\\"') + '")');
                $commandLink.text(command);
                $headline.html(stringifyDate() + ': ');
                $headline.append($commandLink);

                $d.append($headline);

                $.each(response.messages, function() {
                    var $message = $('<div />');
                    $message.addClass(this.type);

                    if (this.message == "") {
                        this.message = "&nbsp;";
                        this.raw = true;
                    }

                    if (this.raw) {
                        $message.html(this.message);

                    } else {
                        $message.text(this.message);
                    }

                    $d.append($message);
                });

                $output.prepend($d);

                if (response.redirect.command) {
                    submitCommand(response.redirect.command, false, response.redirect.internal);
                }
            }

            window.submitCommand = function submitCommand(command, unverifiedExecution, internal)
            {
                if (command == '') {
                    return;
                }

                if (!internal) {
                $command.val(command);
                }

                $status.show();

                $.ajax({
                    url: '<?= $q->e($this->task->module->getRouteUrl('console-parser'), 'js') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        unverifiedExecution: unverifiedExecution,
                        command: command
                    },
                    success: function(response) {
                        $status.hide();

                        processCommandResponse(command, response);
                    },
                    error: function(xhr, r) {
                        $status.hide();

                        var response = {
                            messages: [{
                                type: "error",
                                message: "An unknown error occurred while processing the request."
                            }]
                        };

                        processCommandResponse(command, response);
                    }});
            };

            $form.submit(function(e) {
                submitCommand($command.val(), false);

                e.preventDefault();
            });

            $status.hide();

            <?php
if ($page->command && $page->execute) {
?>
            submitCommand($command.val(), <?= $page->isUnverifiedExecution ? 'true' : 'false' ?>);
            <?php
}
 ?>
        });
    </script>
</head>
<body>
<form action="<?=$q->e($this->task->url)?>" method="post">
    <input type="text" name="command" maxlength="255" size="150" value="<?= $q->e($page->command) ?>" /><a href="<?= $q->e($this->task->route->getUrl()) ?>">Home</a><br />
    <input type="submit" name="execute" value="Execute" /><br />
    <div class="warning statusLoading">Please wait...</div>
    <div class="output">

    </div>
</form>
</body>
</html>