<?php

namespace LarsJanssen\UnderConstruction\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use LarsJanssen\UnderConstruction\Throttle;
use LarsJanssen\UnderConstruction\Facades\TransFormer;

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
     *
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
        return view('views::index')->with([
            'title'       => $this->config['title'],
            'backButton'  => $this->config['back-button'],
            'showButton'  => $this->config['show-button'],
            'hideButton'  => $this->config['hide-button'],
            'showLoader'  => $this->config['show-loader'],
            'totalDigits' => $this->config['total_digits'],
            'redirectUrl' => session()->get('intended.url', $this->config['redirect-url']),
        ]);
    }

    /**
     * Check if the given code is correct,
     * then return the proper response.
     *
     * @param Request $request
     *
     * @throws Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function check(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        if (Hash::check($request->code, $this->getHash())) {
            session(['can_visit' => true]);

            return response([
                'status' => 'success',
            ]);
        }

        $this->incrementLoginAttempts($request);

        if ($this->hasTooManyLoginAttempts($request) && $this->throttleIsActive()) {
            return response([
                'too_many_attempts' => true,
                'seconds_message'  => TransFormer::transform($this->getBlockedSeconds($request), $this->config['seconds_message']),
            ], 401);
        }

        return response([
            'too_many_attempts' => false,
            'attempts_left'    => $this->showAttempts($request),
        ], 401);
    }

    public function checkIfLimited(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request) && $this->throttleIsActive()) {
            return response([
                'too_many_attempts' => true,
                'seconds_message'  => TransFormer::transform($this->getBlockedSeconds($request), $this->config['seconds_message']),
            ], 401);
        }

        return response([
            'too_many_attempts' => false,
        ], 200);
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
     *
     * @return int | bool
     */
    private function showAttempts(Request $request)
    {
        if ($this->config['show_attempts_left'] && $this->config['throttle']) {
            return TransFormer::transform($this->retriesLeft($request), $this->config['attempts_message']);
        }

        return false;
    }

    /**
     * Get the hash from .txt file.
     *
     * @throws Exception
     *
     * @return bool|string
     */
    private function getHash()
    {
        if (isset($this->config['hash']) && $this->config['hash']) {
            return $this->config['hash'];
        } else {
            throw new Exception('Please make sure you have set a code with php artisan code:set ****');
        }
    }
}
