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
    private $response;

    public function parseTask()
    {
        $request = WebConsoleRequest::createFromString(
            $this->task->request->post->asString('command')->asInput(),
            $this->task->request->post->get('unverifiedExecution') == 'true'
        );

        $this->response = new WebConsoleResponse();

        $this->processRequest($request);

        $this->showResponse($this->response);
    }

    public function processRequest(WebConsoleRequest $request)
    {
        $factory = WebConsoleFactory::get($this->app);
        $config = $factory->config();

        $command = str_replace('/', '\\', $request->command);

        if (!preg_match('!^[a-zA-Z0-9_/]+$!', $command)) {
            $this->response->showError('Command not found');

        } else {
            $found = false;

            $className = null;
            foreach ($config->commandNamespaces as $commandNamespace) {
                $className = $commandNamespace . $command . 'Command';

                if (class_exists($className)) {
                    $found = true;
                    break;
                }
            }

            if ($found) {
                /** @var WebConsoleCommand $c */
                $c = new $className();
                $c->request = $request;
                $c->task = $this->task;
                $c->app = $this->app;
                $c->page = $this;

                $c->result = $this->response;
                $this->response->redirectToCommand = $request->args->get('redirect');

                $c->init();

                if (!$request->isUnverifiedExecution || $c->allowUnverifiedExecution) {
                    $c->execute();

                } else {
                    $this->response->showError('Command doesn\'t allow direct execution via URL.');
                }

                $c->deInit();

            } else {
                $this->response->showError('Command not found');
            }
        }

        return $this->response;
    }

    private function showResponse(WebConsoleResponse $response)
    {
        $this->setResult(
            json_encode(
                array(
                    'messages' => $response->messages,
                    'redirect' => array(
                        'command' => $response->redirectToCommand,
                        'internal' => $response->redirectInternal
                    )
                )
            )
        );
    }

    public function parseError($code, $data = null)
    {
        $response = new WebConsoleResponse();
        $response->showError('An unknown error has occurred.');

        $this->showResponse($response);
    }
}
