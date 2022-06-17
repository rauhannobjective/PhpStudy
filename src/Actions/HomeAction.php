<?php

namespace PhpStudy\Src\Actions;

use Slim\Psr7\Message;
use Slim\Psr7\Response;

class HomeAction extends Action
{
    /**
     * @param $request
     * @param $response
     * @param $args
     * @return Message|Response
     */
    public function __invoke($request, $response, $args): Response|Message
    {
        return $this->toJson($response, ['message' => "It's work"]);
    }
}