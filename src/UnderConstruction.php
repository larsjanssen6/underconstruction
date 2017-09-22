<?php

namespace LarsJanssen\UnderConstruction;

use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UnderConstruction
{
    /** @var array */
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('demo-mode');
    }
}