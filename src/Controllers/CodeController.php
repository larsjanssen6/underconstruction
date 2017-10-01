<?php

namespace LarsJanssen\UnderConstruction\Controllers;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LarsJanssen\UnderConstruction\Throttle;
use Illuminate\Config\Repository;

class CodeController extends Controller
{
    use Throttle;

    /**
     * Configurations from config file.
     *
     * @var array
     */
    protected $config;

    /**
     * The maximum number of attempts to allow.
     *
     * @int
     */
    protected $maxAttempts;

    /**
     * The the number of minutes to disable login.
     *
     * @int
     */
    protected $decayMinutes;

    /**
     * CodeController constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('under-construction');
        $this->maxAttempts = $this->config['max_attempts'];
        $this->decayMinutes = $this->config['decay_minutes'];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('views::index');
    }
    
    /**
     * Check if the given code is correct,
     * then return the proper response.
     *
     * @param Request $request
     * @param Hasher $hasher
     * @return \Illuminate\Contracts\Hashing\Hasher
     */
    public function check(Request $request, Hasher $hasher)
    {
        $hash = file_get_contents(__DIR__ . '/../Commands/hash.txt');

        if ($hasher->check($request->code, $hash)) {
            session(['can_visit' => true]);

            return response([
                "status" => "success"
            ]);
        }

        $this->incrementLoginAttempts($request);

        if ($this->hasTooManyLoginAttempts($request) && $this->throttleIsActive()) {
            return response([
                "status" => 'too many attempts',
                'seconds' => $this->getBlockedSeconds($request),
                "too_many_attemps" => true,
            ], 401);
        }

        return response([
            "status" => "wrong code",
            "too_many_attemps" => false,
            'show_attempts_left' => $this->showAttempts($request)
        ], 401);
    }

    /**
     * Determine if throttle is activated in config file.
     *
     * @return bool
     */
    private function throttleIsActive()
    {
        return $this->config['throttle'];
    }

    /**
     * Determine attempts that are left.
     *
     * @param Request $request
     * @return int | bool
     */
    private function showAttempts(Request $request)
    {
        return $this->config['show_attempts_left'] ? $this->retriesLeft($request) : false;
    }
}