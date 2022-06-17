<?php

namespace PhpStudy\Tests\Functional;

use PhpStudy\Tests\TestCase;

class CalculateTotalValueActionFTest extends TestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->seed(\UserFactory::class)->create();
    }

    public function testSumCartWithFreight(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create([
            'value' => 10
        ]);

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id']
        ]);

        $response = $this->runApp("POST", "cart/total/{$cart['id']}", [
            'zipcode' => 37500000
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(13, $responseData['data']);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testSumCartWithoutFreight(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create([
            'value' => 150
        ]);

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 2
        ]);

        $response = $this->runApp("POST", "cart/total/{$cart['id']}", [
            'zipcode' => 37500000
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(300, $responseData['data']);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testSumCartWithoutZipcode(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create([
            'value' => 150
        ]);

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 2
        ]);

        $response = $this->runApp("POST", "cart/total/{$cart['id']}");

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('zipcode must be present', $responseData['errors'][0]);
    }

    public function testSumCartWithNotExistsCart(): void
    {
        $response = $this->runApp("POST", "cart/total/999", [
            'zipcode' => 37500000
        ]);

        $responseData = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey(0, $responseData['errors']);
        $this->assertEquals('Carrinho não encontrado!', $responseData['errors'][0]);
    }
}