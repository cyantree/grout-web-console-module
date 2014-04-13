<?php
namespace Grout\Cyantree\WebConsoleModule\Types;

use Cyantree\Grout\Filter\ArrayFilter;

class WebConsoleResponse
{
    public $content = '';

    public $messages = array();

    public $redirectToCommand = null;
    public $redirectInternal = true;

    public function __construct()
    {
        $this->data = new ArrayFilter();
    }

    public function redirectTo($command, $internal = true)
    {
        $this->redirectToCommand = $command;
        $this->redirectInternal = $internal;
    }

    public function showSuccess($message, $raw = false)
    {
        $this->messages[] = array(
            'type' => 'success',
            'message' => strval($message),
            'raw' => $raw
        );
    }

    public function showInfo($message, $raw = false)
    {
        $this->messages[] = array(
            'type' => 'info',
            'message' => strval($message),
            'raw' => $raw
        );
    }

    public function showWarning($message, $raw = false)
    {
        $this->messages[] = array(
            'type' => 'warning',
            'message' => strval($message),
            'raw' => $raw
        );
    }

    public function showError($message, $raw = false)
    {
        $this->messages[] = array(
            'type' => 'error',
            'message' => strval($message),
            'raw' => $raw
        );
    }
}