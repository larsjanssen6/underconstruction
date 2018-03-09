<?php

namespace LarsJanssen\UnderConstruction;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UnderConstruction
{
    /**
     * @var mixed
     */
    protected $config;

    /**
     * UnderConstruction constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('under-construction');
    }

    /**
     * @param $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->config['enabled'] || $request->is('under/*')) {
            return $next($request);
        }

        if (! $this->hasAccess($request)) {
            return new RedirectResponse('/under/construction');
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function hasAccess(Request $request)
    {
        return session()->has('can_visit');
    }
}
