<?php
namespace Grout\Cyantree\WebConsoleModule;

use Cyantree\Grout\App\Module;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleConfig;

class WebConsoleModule extends Module
{
    public function init()
    {
        $this->addNamedRoute('console', '', 'Pages\WebConsolePage');
        $this->addNamedRoute('console-parser', 'parser/', 'Pages\WebConsoleParserPage');

        $this->app->configs->setDefaultConfig($this->id, new WebConsoleConfig());
    }
}