<?php

namespace LarsJanssen\UnderConstruction;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;

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
        if ($request->is($this->config['route-prefix'].'/*')) {
            return $next($request);
        }

        if (! $this->config['enabled']) {
            return $next($request);
        }

        if (! $this->hasAccess($request)) {
            session(['intended.url' => url()->current()]);

            return new RedirectResponse('/'.$this->config['route-prefix'].'/'.$this->config['custom-endpoint']);
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
