<?php

namespace PhpStudy\Src\Actions\Cart;

use Exception;
use PhpStudy\Src\Actions\Action;
use PhpStudy\Src\Models\CartModel;
use Slim\Psr7\Message;
use Slim\Psr7\Response;

class CalculateTotalValueAction extends Action
{
    public CartModel $cartModel;

    public function __construct(CartModel $cartModel)
    {
        $this->cartModel = $cartModel;
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return Message|Response
     * @throws Exception
     */
    public function __invoke($request, $response, $args): Response|Message
    {
        $params = $request->getParsedBody();

        $result = $this->cartModel->calculateTotalCart(
            $args['cartId'],
            $params['zipcode']
        );

        return $this->toJson($response, ['data' => $result]);
    }
}