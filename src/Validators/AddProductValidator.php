<?php

namespace PhpStudy\Src\Validators;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Request;
use Respect\Validation\Validator as v;

class AddProductValidator extends Validator
{
    /**
     * @param Request $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function __invoke(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $rules = [
            'product_quantity' => v::key('product_quantity', v::intVal()->greaterThan(0)->notEmpty()),
            'product_id' => v::key('product_id', v::intVal()->greaterThan(0)->notEmpty())
        ];

        return $this->process($handler, $request, $this->getAttributes($request), $rules, $this->messages());
    }

    /**
     * @return array
     */
    private function messages(): array
    {
        return [
            'product_quantity' => 'Campo product_quantity: obrigatório, ser um inteiro válido, ser maior que zero',
            'product_id' => 'Campo product_id: obrigatório, ser um inteiro válido, ser maior que zero',
        ];
    }
}