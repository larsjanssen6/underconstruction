<?php

namespace LarsJanssen\UnderConstruction\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AssetController extends Controller
{
    /**
     * Return compiled vue.js output.
     *
     * @return Response
     */
    public function js()
    {
        $content = file_get_contents(__DIR__.'/../../output/app.js');

        $response = new Response(
            $content, 200, [
                'Content-Type' => 'text/javascript',
            ]
        );

        return $response;
    }
}
