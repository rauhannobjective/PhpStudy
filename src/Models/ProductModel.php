<?php

namespace PhpStudy\Src\Models;

use PhpStudy\Src\Entities\Product;
use PhpStudy\Src\Exceptions\JsonException;

class ProductModel
{
    public Product $productEntity;

    public function __construct(Product $productEntity)
    {
        $this->productEntity = $productEntity;
    }

    /**
     * Retorna um produto pelo seu Id
     *
     * @param int $id
     * @return array
     * @throws JsonException
     */
    public function getById(int $id): array
    {
        $product = $this->productEntity->findOneBy([
            'id' => $id
        ]);

        if (!$product) {
            throw new JsonException('Produto não encontrado!');
        }

        return $product;
    }
}