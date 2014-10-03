<?php
namespace Grout\Cyantree\WebConsoleModule;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\GroutFactory;
use Grout\AppModule\AppFactory;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleConfig;

class WebConsoleFactory extends AppFactory
{
    /** @var WebConsoleModule */
    public $module;

    /** @return WebConsoleFactory */
    public static function get(App $app = null, $moduleId = null)
    {
        /** @var WebConsoleFactory $factory */
        $factory = GroutFactory::_getInstance($app, __CLASS__, $moduleId, 'Cyantree\WebConsoleModule');

        return $factory;
    }

    public function config()
    {
        if($tool = $this->_getAppTool(__FUNCTION__, __CLASS__)){
            return $tool;
        }

        /** @var WebConsoleConfig $tool */
        $tool = $this->app->configs->getConfig($this->module->id);

        $this->_setAppTool(__FUNCTION__, $tool);
        return $tool;
    }
}