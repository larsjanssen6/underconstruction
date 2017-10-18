<?php

namespace LarsJanssen\UnderConstruction;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UnderConstruction
{
    protected $config;
    
    public function __construct(Repository $config)
    {
        $this->config = $config->get('under-construction');
    }

    public function handle($request, Closure $next)
    {
        if (! $this->config['enabled']) {
            return $next($request);
        }

        if (! $this->hasAccess($request)) {
            return new RedirectResponse($this->config['construction_link']);
        }

        return $next($request);
    }

    protected function hasAccess(Request $request)
    {
        return session()->has('can_visit');
    }
}
