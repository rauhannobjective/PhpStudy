<?php

namespace PhpStudy\Tests\Functional;

use PhpStudy\Tests\TestCase;

class AddProductActionFTest extends TestCase
{
    private $user;
    private $cart;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->seed(\UserFactory::class)->create();

        $this->cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);
    }

    /**
     * Adicionando 1 produto ao carrinho
     *
     * @return void
     */
    public function testAddProductSuccess(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->runApp("PATCH", "cart/add-product/{$this->cart['id']}", [
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Adicionado", $responseData['message']);

        $this->seeInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);
    }

    /**
     * Adicionando um produto que já existe no carrinho
     *
     * @return void
     */
    public function testAddExistingProductSuccess(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);

        $this->seeInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);

        $response = $this->runApp("PATCH", "cart/add-product/{$this->cart['id']}", [
            'product_id' => $product['id'],
            'product_quantity' => 3
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Atualizado", $responseData['message']);

        $this->seeInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 3
        ]);

        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);
    }

    /**
     * Erro ao adicionar zero produtos em um carrinho.
     *
     * @return void
     */
    public function testAddProductWithZeroProductQuantity(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->runApp("PATCH", "cart/add-product/{$this->cart['id']}", [
            'product_id' => $product['id'],
            'product_quantity' => 0
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('product_quantity must be greater than 0', $responseData['errors'][0]);

        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 0
        ]);
    }

    /**
     * Erro ao adicionar produtos sem quantidade
     *
     * @return void
     */
    public function testAddProductWithoutProductQuantity(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->runApp("PATCH", "cart/add-product/{$this->cart['id']}", [
            'product_id' => $product['id'],
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('product_quantity must be present', $responseData['errors'][0]);

        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
        ]);
    }

    /**
     * Erro ao adicionar produtos sem existir produto
     *
     * @return void
     */
    public function testAddProductWithoutProductId(): void
    {
        $response = $this->runApp("PATCH", "cart/add-product/{$this->cart['id']}", [
            'product_id' => 22222,
            'product_quantity' => 1
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('Produto não encontrado!', $responseData['errors'][0]);

        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => 22222,
        ]);
    }

    /**
     * Erro ao adicionar produtos sem parametrizar produto
     *
     * @return void
     */
    public function testAddProductNotIssetProductId(): void
    {
        $response = $this->runApp("PATCH", "cart/add-product/{$this->cart['id']}", [
            'product_quantity' => 1
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('product_id must be present', $responseData['errors'][0]);

        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_quantity' => 1
        ]);
    }

    /**
     * Erro ao adicionar produtos sem existir o carrinho
     *
     * @return void
     */
    public function testAddProductWithoutCart(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->runApp("PATCH", "cart/add-product/222", [
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('Carrinho não encontrado!', $responseData['errors'][0]);

        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_quantity' => 1
        ]);
    }
}