<?php

namespace PhpStudy\Src\Actions\Cart;

use Exception;
use PhpStudy\Src\Actions\Action;
use PhpStudy\Src\Models\CartModel;
use Slim\Psr7\Message;
use Slim\Psr7\Response;

class AddProductAction extends Action
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

        $result = $this->cartModel->addProduct(
            $args['cartId'],
            $params['product_id'],
            $params['product_quantity']
        );

        return $this->toJson($response, ["message" => $result]);
    }
}