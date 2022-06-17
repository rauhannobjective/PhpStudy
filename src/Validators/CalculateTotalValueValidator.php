<?php

namespace PhpStudy\Src\Validators;

use PhpStudy\Src\Traits\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Message;
use Slim\Psr7\Request;
use Respect\Validation\Validator as v;
use Slim\Psr7\Response;

class CalculateTotalValueValidator extends Validator
{
    use Json;

    /**
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return Response|Message|ResponseInterface
     */
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response|Message|ResponseInterface
    {
        $rules = [
            v::key('zipcode', v::postalCode('BR'))
        ];

        return $this->process($handler, $request, $this->getAttributes($request), $rules, $this->messages());
    }

    /**
     * @return string[]
     */
    private function messages(): array
    {
        return [
            'zipcode' => 'CEP inválido para o país'
        ];
    }
}