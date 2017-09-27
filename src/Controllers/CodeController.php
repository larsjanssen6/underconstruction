<?php

namespace LarsJanssen\UnderConstruction\Controllers;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LarsJanssen\UnderConstruction\Throttle;

class CodeController extends Controller
{
    use Throttle;

    public function index()
    {
        return view('views::index');
    }

    /**
     * @param Request $request
     * @param Hasher $hasher
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function check(Request $request, Hasher $hasher)
    {
        if (!$this->hasTooManyLoginAttempts($request)) {
            $hash = file_get_contents(__DIR__ . '/../Commands/hash.txt');

            if ($hasher->check($request->code, $hash)) {
                session(['can_visit' => true]);

                return response([
                    "status" => "success"
                ]);
            }

            $this->incrementLoginAttempts($request);

            return response([
                "status" => "wrong code",
                "too_many_attemps" => false
            ], 401);
        }

        return response([
            "status" => 'too many attempts',
            'seconds' => $this->getBlockedSeconds($request),
            "too_many_attemps" => true
        ], 401);
    }
}