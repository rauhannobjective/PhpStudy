<?php

namespace PhpStudy\Tests\Unit\Models;

use PhpStudy\Src\Entities\Product;
use PhpStudy\Src\Exceptions\JsonException;
use PhpStudy\Src\Models\ProductModel;
use PhpStudy\Tests\TestCase;

class ProductModelUTest extends TestCase
{
    private ProductModel $productModel;

    public function setup(): void
    {
        parent::setUp();
        $this->productModel = new ProductModel(new Product());
    }

    /**
     * @throws JsonException
     */
    public function testGetByIdModel(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->productModel->getById($product['id']);

        $this->assertIsArray($response);
        $this->assertEquals($product['id'], $response['id']);
        $this->assertEquals($product['name'], $response['name']);
        $this->assertEquals($product['value'], $response['value']);
    }

    /**
     * @throws JsonException
     */
    public function testGetByIdNotExistsModel(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Produto não encontrado!');
        $this->expectExceptionCode(400);

        $this->productModel->getById(123);
    }
}
