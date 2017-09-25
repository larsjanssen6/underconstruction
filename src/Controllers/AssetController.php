<?php

namespace LarsJanssen\UnderConstruction\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AssetController extends Controller
{
    public function js()
    {
        $content = file_get_contents(__DIR__ . '/../../output/app.js');

        $response = new Response(
            $content, 200, [
                'Content-Type' => 'text/javascript',
            ]
        );

        return $response;
    //        return $this->cacheResponse($response);
    }

    /**
     * Cache the response 1 year (31536000 sec)
     * @param Response $response
     * @return Response
     */
    protected function cacheResponse(Response $response)
    {
        $response->setSharedMaxAge(31536000);
        $response->setMaxAge(31536000);
        $response->setExpires(new \DateTime('+1 year'));
        return $response;
    }
}