<?php
namespace Grout\Cyantree\WebConsoleModule\Types;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Task;

use Cyantree\Grout\Tools\StringTools;
use Grout\Cyantree\WebConsoleModule\Pages\WebConsolePage;
use Grout\Cyantree\WebConsoleModule\WebConsoleFactory;

class WebConsoleCommand
{
    /** @var Task */
    public $task;

    /** @var App */
    public $app;

    /** @var WebConsolePage */
    public $page;

    /** @var WebConsoleRequest */
    public $request;

    /** @var WebConsoleResponse */
    public $result;

    public $isUnverifiedExecution = false;
    public $allowUnverifiedExecution = false;

    public function show($text, $raw = false, $type = 'info')
    {
        if ($type == 'success') {
            $this->result->showSuccess($text, $raw);

        } elseif ($type == 'info') {
            $this->result->showInfo($text, $raw);

        } elseif ($type == 'warning') {
            $this->result->showWarning($text, $raw);

        } elseif ($type == 'error') {
            $this->result->showError($text, $raw);
        }
    }
    /*
    public function getCommandUrl($command, $execute = true)
    {
        return $this->task->module->getRouteUrl('console') . '?command=' . rawurlencode($command) . ($execute ? '&execute=true' : '');
    }
    */
    public function generateCommandLink($command, $title = null)
    {
        return '<a href="javascript:submitCommand(\'' . StringTools::escapeHtml($command) . '\')">' . StringTools::escapeHtml($title ? $title : $command) . '</a>';
    }

    public function showHeadline($title, $rawTitle = false, $level = 2)
    {
        $this->show('<h' . $level . '>' . ($rawTitle ? $title : StringTools::escapeHtml($title)) . '</h' . $level . '>', true);
    }

    /** @return WebConsoleFactory */
    public function factory()
    {
        return WebConsoleFactory::get($this->app);
    }

    public function init()
    {

    }

    public function deInit()
    {

    }

    public function execute()
    {

    }

    public function onError()
    {

    }
}