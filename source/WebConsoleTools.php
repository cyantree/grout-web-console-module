<?php
namespace Grout\Cyantree\WebConsoleModule;

use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Tools\ServerTools;

class WebConsoleTools
{
    public static function constructCommandString($command, $args = null)
    {
        if (!$args) {
            return $command;
        }

        $string = $command;

        if ($args instanceof ArrayFilter) {
            $args = $args->getData();
        }

        foreach ($args as $arg => $value) {
            if ($value === null) {
                continue;
            }

            if ($value === true) {
                $string .= ' --' . $arg;
            } else {
                $value = str_replace(array('\\', '"'), array('\\\\', '\"'), $value);
                if (strpos($value, ' ') !== false) {
                    $value = '"' . $value . '"';
                }

                if (is_int($arg)) {
                    $string .= ' ' . $value;

                } else {
                    $string .= ' -' . $arg . '=' . $value;
                }
            }
        }

        return $string;
    }
}