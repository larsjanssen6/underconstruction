<?php

namespace LarsJanssen\UnderConstruction\Controllers;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CodeController extends Controller
{
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
        $hash = file_get_contents(__DIR__ . '/../Commands/temp.txt');

        if ($hasher->check($request->code, $hash)) {
            session(['can_visit' => true]);

            return response([
                "status" => "success"
            ]);
        }

        return response([
            "status" => "failed"
        ], 401);
    }
}