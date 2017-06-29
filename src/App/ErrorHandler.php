<?php

namespace App;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
* Generic error handler. Not Allowed, Not Found, etc.
*/
class ErrorHandler
{
    public static function notAllowed(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = ["error" => "sorry buddy you are busted!"];
        return $response
                ->withStatus(405)
                ->withJson($data);
    }

    public static function notFound(ServerRequestInterface $request, ResponseInterface $response)
    {

        $data = ["error" => "Hey hey! There is nothing to see here!"];
        return $response
            ->withStatus(404)
            ->withJson($data);
    }

    public static function runTimeError(ServerRequestInterface $request, ResponseInterface $response)
    {

        $data = ["error" => "something bad just happened!"];
        return $response
            ->withStatus(500)
            ->withJson($data);
    }
}
