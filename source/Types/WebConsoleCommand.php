<?php
namespace Grout\Cyantree\WebConsoleModule\Types;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Task;
use Cyantree\Grout\Filter\ArrayFilter;

class WebConsoleCommand
{
    /** @var ArrayFilter */
    public $args;

    /** @var Task */
    public $task;

    /** @var App */
    public $app;

    /** @var WebConsoleResult */
    public $result;

    public $command;

    /** @var ArrayFilter */
    public $postData;

    public function show($text, $newLine = true)
    {
        if($newLine){
            $this->result->result .= $text.chr(10);
        }else{
            $this->result->result .= $text;
        }
    }

    public function execute()
    {

    }

    public function onError()
    {

    }
}