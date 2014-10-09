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
        $factory = GroutFactory::getFactory($app, __CLASS__, $moduleId, 'Cyantree\WebConsoleModule');

        return $factory;
    }

    public function config()
    {
        if (!($tool = $this->getTool(__FUNCTION__, false))) {
            /** @var WebConsoleConfig $tool */
            $tool = $this->app->configs->getConfig($this->module->id);

            $this->setTool(__FUNCTION__, $tool);
        }

        return $tool;
    }
}
