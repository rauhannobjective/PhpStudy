<?php

namespace PhpStudy\Tests\Functional;

use PhpStudy\Tests\TestCase;

class RemoveProductActionFTest extends TestCase
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
     * Removendo 1 produto do carrinho
     *
     * @return void
     */
    public function testRemoveProductSuccess(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);

        $response = $this->runApp("PATCH", "cart/remove-product/{$this->cart['id']}", [
            'product_id' => $product['id'],
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Deletado", $responseData['message']);

        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $this->cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);
    }


    /**
     * Erro ao remover produtos sem existir produto
     *
     * @return void
     */
    public function testAddProductWithoutProductId(): void
    {
        $response = $this->runApp("PATCH", "cart/remove-product/{$this->cart['id']}", [
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
     * Erro ao remover produtos sem parametrizar produto
     *
     * @return void
     */
    public function testRemoveProductNotIssetProductId(): void
    {
        $response = $this->runApp("PATCH", "cart/remove-product/{$this->cart['id']}");

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('product_id must be present', $responseData['errors'][0]);
    }

    /**
     * Erro ao remover produtos sem existir o carrinho
     *
     * @return void
     */
    public function testAddProductWithoutCart(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->runApp("PATCH", "cart/remove-product/222", [
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