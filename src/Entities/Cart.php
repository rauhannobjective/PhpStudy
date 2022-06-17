<?php

namespace PhpStudy\Src\Entities;

use PhpStudy\Src\Exceptions\JsonException;

class Cart extends Entity
{
    protected string $table = 'carts';

    /**
     * Retorna carrinho com seus produtos.
     *
     * @param int $cartId
     * @return mixed
     * @throws JsonException
     */
    public function getByIdWithProducts(int $cartId): mixed
    {
        $stmt = $this->query("
            SELECT
            c.id AS cart_id,
            cp.product_id,
            p.name AS product_name,
            p.value AS product_value, 
            cp.product_quantity 
            FROM {$this->table} c 
            LEFT JOIN carts_products cp ON cp.cart_id = c.id 
            LEFT JOIN products p ON cp.product_id = p.id
            WHERE c.id = ?", [
            $cartId
        ]);

        if ($stmt) {
            return $stmt->fetchAll();
        }

        throw new JsonException('Erro ao consultar carrinho');
    }

    /**
     * Retorna produto do carrinho.
     *
     * @param int $cartId
     * @param int $productId
     * @return mixed
     * @throws JsonException
     */
    public function getProductByCart(
        int $cartId,
        int $productId
    ): mixed
    {
        $stmt = $this->query("
            SELECT
            cart_id,
            product_id,
            product_quantity 
            FROM carts_products 
            WHERE cart_id = ? AND product_id = ?", [
            $cartId,
            $productId
        ]);

        if ($stmt) {
            return $stmt->fetch();
        }

        throw new JsonException('Erro ao consultar pivot de produtos e carrinhos');
    }
}