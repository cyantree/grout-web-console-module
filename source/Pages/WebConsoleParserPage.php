<?php
namespace Grout\Cyantree\WebConsoleModule\Pages;

use Cyantree\Grout\App\Page;


use Cyantree\Grout\ErrorHandler;
use Cyantree\Grout\ErrorWrapper\ErrorWrapper;


use Grout\Cyantree\WebConsoleModule\Types\WebConsoleCommand;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleRequest;
use Grout\Cyantree\WebConsoleModule\Types\WebConsoleResponse;
use Grout\Cyantree\WebConsoleModule\WebConsoleFactory;

class WebConsoleParserPage extends Page
{
    /** @var WebConsoleResponse */
    private $_response;

    public function parseTask()
    {
        $request = WebConsoleRequest::createFromString($this->task->request->post->asString('command')->asInput(), $this->task->request->post->get('unverifiedExecution') == 'true');

        $this->_response = new WebConsoleResponse();

        ErrorWrapper::register();

        try {
            $this->processRequest($request);

        } catch (\Exception $e) {
            $this->app->events->trigger('logException', $e);
            $this->_response->showError('An unknown error has occurred.');
            $this->_response->redirectInternal = false;
            $this->_response->redirectToCommand = null;
        }

        ErrorWrapper::unregister();

        $this->_showResponse($this->_response);
    }

    public function processRequest(WebConsoleRequest $request){
        $factory = WebConsoleFactory::get($this->app);
        $config = $factory->appConfig();

        $command = str_replace('/', '\\', $request->command);

        if(!preg_match('!^[a-zA-Z0-9_/]+$!', $command)){
            $this->_response->showError('Command not found');

        }else{
            $found = false;

            $className = null;
            foreach($config->commandNamespaces as $commandNamespace){
                $className = $commandNamespace.$command.'Command';

                if(class_exists($className)){
                    $found = true;
                    break;
                }
            }

            if($found){
                /** @var WebConsoleCommand $c */
                $c = new $className();
                $c->request = $request;
                $c->task = $this->task;
                $c->app = $this->app;
                $c->page = $this;

                $c->result = $this->_response;
                $this->_response->redirectToCommand = $request->args->get('redirect');

                $c->init();

                if (!$request->isUnverifiedExecution || $c->allowUnverifiedExecution) {
                    $c->execute();

                } else {
                    $this->_response->showError('Command doesn\'t allow direct execution via URL.');
                }

                $c->deInit();

            }else{
                $this->_response->showError('Command not found');
            }
        }

        return $this->_response;
    }

    private function _showResponse(WebConsoleResponse $response)
    {
        $this->setResult(json_encode(array(
                    'messages' => $response->messages,
                    'redirect' => array(
                        'command' => $response->redirectToCommand,
                        'internal' => $response->redirectInternal
                    )
                )));
    }

    public function parseError($code, $data = null)
    {
        $response = new WebConsoleResponse();
        $response->showError('An unknown error has occurred.');

        $this->_showResponse($response);
    }
}