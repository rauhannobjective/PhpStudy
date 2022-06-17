<?php

namespace PhpStudy\Src\Validators;

use PhpStudy\Src\Traits\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class Validator
{
    use Json;

    /**
     * Retorna campos.
     *
     * @param Request $request
     * @return array
     */
    public function getAttributes(Request $request): array
    {
        return $request->getParsedBody() ?? [];
    }

    /**
     * Faz a validação dos requests vindos das requisições.
     *
     * @param RequestHandlerInterface $handler
     * @param Request $request
     * @param array $attributes
     * @param array $rules
     * @param array $messages
     * @return Response|Message|ResponseInterface
     */
    public function process(
        RequestHandlerInterface $handler,
        Request                 $request,
        array                   $attributes,
        array                   $rules,
        array                   $messages
    ): Response|Message|ResponseInterface
    {
        try {
            foreach ($rules as $field) {
                $field->check($attributes);
            }

            return $handler->handle($request);
        } catch (NestedValidationException|ValidationException $e) {
            return $this->toJson(new Response(), ["errors" => [$e->getMessage()]], 422);
        }
    }
}