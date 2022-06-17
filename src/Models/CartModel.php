<?php

namespace PhpStudy\Src\Models;

use PhpStudy\Src\Entities\Cart;
use PhpStudy\Src\Entities\CartProduct;
use PhpStudy\Src\Exceptions\JsonException;
use PhpStudy\Src\Services\CorreiosService;

class CartModel
{
    public Cart $cartEntity;
    public ProductModel $productModel;
    public CartProduct $cartProductEntity;
    public CorreiosService $freightInterface;

    public function __construct(
        Cart            $cartEntity,
        ProductModel    $productModel,
        CartProduct     $cartProductEntity,
        CorreiosService $freightInterface,
    )
    {
        $this->cartEntity = $cartEntity;
        $this->productModel = $productModel;
        $this->cartProductEntity = $cartProductEntity;
        $this->freightInterface = $freightInterface;
    }

    /**
     * Calcula o total de um carrinho considerando o frete ou não.
     *
     * @param integer $cartId
     * @param string $zipCode
     * @return float
     * @throws JsonException
     */
    public function calculateTotalCart(
        int    $cartId,
        string $zipCode
    ): float
    {
        $cartsProducts = $this->getByIdWithProducts($cartId);
        $totalProducts = $this->sumAllProducts($cartsProducts);

        return $this->freightInterface->applyFreight($totalProducts, $zipCode);
    }

    /**
     * Adiciona um produto ao carrinho.
     *
     * @param integer $cartId
     * @param integer $productId
     * @param integer $quantity
     * @return string
     * @throws JsonException
     */
    public function addProduct(
        int $cartId,
        int $productId,
        int $quantity
    ): string
    {
        $cart = $this->getById($cartId);
        $product = $this->productModel->getById($productId);
        $cartProduct = $this->getProductByCart($cart['id'], $product['id']);
        $result = "Adicionado";

        if ($cartProduct) {
            $this->cartProductEntity->update([
                'product_quantity' => $quantity
            ], [
                'cart_id' => $cartId,
                'product_id' => $productId
            ]);
            $result = "Atualizado";
        } else {
            $this->cartProductEntity->save([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'product_quantity' => $quantity
            ]);
        }

        return $result;
    }

    /**
     * Remove um produto do carrinho.
     *
     * @param int $cartId
     * @param int $productId
     * @return string
     * @throws JsonException
     */
    public function removeProduct(
        int $cartId,
        int $productId
    ): string
    {
        $cart = $this->getById($cartId);
        $product = $this->productModel->getById($productId);

        $this->cartProductEntity->delete([
            'cart_id' => $cart['id'],
            'product_id' => $product['id']
        ]);

        return "Deletado";
    }

    /**
     * Retorna um carrinho pelo seu Id com seus respectivos produtos.
     *
     * @param int $cartId
     * @return array
     * @throws JsonException
     */
    public function getByIdWithProducts(int $cartId): array
    {
        $cart = $this->cartEntity->getByIdWithProducts($cartId);

        if (!$cart) {
            throw new JsonException("Carrinho não encontrado!");
        }

        return $cart;
    }

    /**
     * Retorna um carrinho pelo seu Id
     *
     * @param int $id
     * @return array
     * @throws JsonException
     */
    public function getById(int $id): array
    {
        $cart = $this->cartEntity->findOneBy([
            'id' => $id
        ]);

        if (!$cart) {
            throw new JsonException("Carrinho não encontrado!");
        }

        return $cart;
    }

    /**
     * Retorna se o produto está no carrinho.
     *
     * @param int $cartId
     * @param int $productId
     * @return array
     * @throws JsonException
     */
    public function getProductByCart(
        int $cartId,
        int $productId
    ): array
    {
        $cartProduct = $this->cartEntity->getProductByCart($cartId, $productId);

        if (!$cartProduct) {
            $cartProduct = [];
        }

        return $cartProduct;
    }

    /**
     * Soma os valores dos produtos.
     *
     * @param array $products
     * @return float
     */
    private function sumAllProducts(array $products): float
    {
        $total = 0;

        if ($products) {
            foreach ($products as $product) {
                $total += $product['product_value'] * $product['product_quantity'];
            }
        }

        return $total;
    }
}