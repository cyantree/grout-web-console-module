<?php
namespace Grout\Cyantree\WebConsoleModule\Types;

use Cyantree\Grout\Filter\ArrayFilter;

class WebConsoleResult
{
    public $result = '';

    /** @var ArrayFilter */
    public $data;

    public $redirectToCommand = null;
    public $redirectToCommandDelay = 1;

    public function __construct()
    {
        $this->data = new ArrayFilter();
    }
}