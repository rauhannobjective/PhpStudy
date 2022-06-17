<?php

namespace PhpStudy\Tests\Unit\Entities;

use PhpStudy\Src\Entities\Cart;
use PhpStudy\Src\Exceptions\JsonException;
use PhpStudy\Tests\TestCase;

class CartUTest extends TestCase
{
    private $user;
    private Cart $cart;

    public function setup(): void
    {
        parent::setUp();
        $this->cart = new Cart();
        $this->user = $this->seed(\UserFactory::class)->create();
    }

    /**
     * @throws JsonException
     */
    public function testGetByIdWithProducts(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create();

        $cartProduct = $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 2
        ]);

        $response = $this->cart->getByIdWithProducts($cart['id']);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('cart_id', $response[0]);
        $this->assertArrayHasKey('product_id', $response[0]);
        $this->assertArrayHasKey('product_name', $response[0]);
        $this->assertArrayHasKey('product_value', $response[0]);
        $this->assertArrayHasKey('product_quantity', $response[0]);

        $this->assertEquals($cartProduct['cart_id'], $response[0]['cart_id']);
        $this->assertEquals($cartProduct['product_id'], $response[0]['product_id']);
        $this->assertEquals($cartProduct['product_quantity'], $response[0]['product_quantity']);
        $this->assertEquals($product['name'], $response[0]['product_name']);
        $this->assertEquals($product['value'], $response[0]['product_value']);
    }

    /**
     * @throws JsonException
     */
    public function testGetByIdWithoutProducts(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $response = $this->cart->getByIdWithProducts($cart['id']);

        $this->assertIsArray($response);
        $this->assertEquals($cart['id'], $response[0]['cart_id']);
        $this->assertEquals(null, $response[0]['product_id']);
        $this->assertEquals(null, $response[0]['product_name']);
        $this->assertEquals(null, $response[0]['product_value']);
        $this->assertEquals(null, $response[0]['product_quantity']);
    }

    /**
     * @throws JsonException
     */
    public function testGetByIdWithoutCart(): void
    {
        $response = $this->cart->getByIdWithProducts(123);

        $this->assertIsArray($response);
        $this->assertEquals([], $response);
    }
}