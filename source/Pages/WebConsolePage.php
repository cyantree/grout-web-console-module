<?php
namespace Grout\Cyantree\WebConsoleModule\Pages;

use Cyantree\Grout\App\Page;


use Cyantree\Grout\Tools\ServerTools;


use Grout\Cyantree\WebConsoleModule\WebConsoleFactory;

class WebConsolePage extends Page
{
    public $command;
    public $execute;
    public $isUnverifiedExecution;

    public function parseTask()
    {
        $factory = WebConsoleFactory::get($this->app);

        $this->command = $this->task->request->get->get('command');
        $this->execute = $this->task->request->get->get('execute') == 'true';
        $this->isUnverifiedExecution = true;

        if ($this->command == '') {
            $this->command = $factory->appConfig()->defaultCommand;
            $this->execute = true;
            $this->isUnverifiedExecution = false;
        }

        $this->setResult($factory->appTemplates()->load('CyantreeWebConsoleModule::console.html', null, false));
    }
}