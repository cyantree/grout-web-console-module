<?php
namespace Grout\Cyantree\WebConsoleModule\Types;

use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Tools\ServerTools;
use Grout\Cyantree\WebConsoleModule\WebConsoleTools;

class WebConsoleRequest
{
    public $fullCommand;

    public $command;

    public $isUnverifiedExecution = false;

    /** @var ArrayFilter */
    public $args;

    /** @var ArrayFilter */
    public $data;

    public function __construct()
    {
        $this->args = new ArrayFilter();
        $this->data = new ArrayFilter();
    }

    public static function createFromString($command, $unverifiedExecution = false)
    {
        if ($command == '') {
            return null;
        }

        $request = new WebConsoleRequest();

        $args = ServerTools::parseCommandlineString($command);

        $request->command = $args[0];
        $request->isUnverifiedExecution = $unverifiedExecution;

        $get = array();
        if (count($args) > 1) {
            $args = array_splice($args, 1);
            foreach ($args as $arg) {
                if (substr($arg, 0, 2) == '--') {
                    $get[substr($arg, 2)] = true;

                } elseif (substr($arg, 0, 1) == '-') {
                    $s = explode('=', $arg, 2);

                    $get[substr($s[0], 1)] = count($s) == 2 ? $s[1] : '';

                } else {
                    $get[] = $arg;
                }
            }
        }

        $request->args = new ArrayFilter($get);
        $request->fullCommand = WebConsoleTools::constructCommandString($request->command, $request->args);

        return $request;
    }

    public function toNewCommandString($mergeArgs)
    {
        return WebConsoleTools::constructCommandString($this->command, array_merge($this->args->getData(), $mergeArgs));
    }
}
