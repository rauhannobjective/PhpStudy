<?php

namespace PhpStudy\Tests\Unit\Models;

use PhpStudy\Src\Entities\Cart;
use PhpStudy\Src\Entities\CartProduct;
use PhpStudy\Src\Entities\Product;
use PhpStudy\Src\Exceptions\JsonException;
use PhpStudy\Src\Models\CartModel;
use PhpStudy\Src\Models\ProductModel;
use PhpStudy\Tests\Mocks\MockCorreiosService;
use PhpStudy\Tests\TestCase;

class CartModelUTest extends TestCase
{
    private $user;
    private CartModel $cartModel;

    public function setup(): void
    {
        parent::setUp();
        $this->cartModel = new CartModel(new Cart(), new ProductModel(new Product()), new CartProduct(), new MockCorreiosService());
        $this->user = $this->seed(\UserFactory::class)->create();
    }

    /**
     * @throws JsonException
     */
    public function testCalculateTotalCartWithFreight(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create([
            'value' => 10
        ]);

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 2
        ]);

        $response = $this->cartModel->calculateTotalCart($cart['id'], 37500000);

        $this->assertEquals(23, $response);
        $this->assertIsNumeric($response);
    }

    /**
     * @throws JsonException
     */
    public function testCalculateTotalCartWithoutFreight(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create([
            'value' => 200
        ]);

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 2
        ]);

        $response = $this->cartModel->calculateTotalCart($cart['id'], 37500000);

        $this->assertEquals(400, $response);
        $this->assertIsNumeric($response);
    }

    public function testCalculateTotalCartWithoutCart(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Carrinho não encontrado!');
        $this->expectExceptionCode(400);

        $this->cartModel->calculateTotalCart(123, 37500000);
    }

    /**
     * @throws JsonException
     */
    public function testCalculateTotalCartWithoutProducts(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $response = $this->cartModel->calculateTotalCart($cart['id'], 37500000);

        $this->assertEquals(0, $response);
        $this->assertIsNumeric($response);
    }

    /**
     * @throws JsonException
     */
    public function testAddProductNotExistsInCartModel(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->cartModel->addProduct($cart['id'], $product['id'], 1);

        $this->assertEquals('Adicionado', $response);
        $this->assertIsString($response);
        $this->seeInDatabase('carts_products', [
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 1
        ]);
    }

    /**
     * @throws JsonException
     */
    public function testAddProductExistsInCartModel(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create();

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 4
        ]);

        $response = $this->cartModel->addProduct($cart['id'], $product['id'], 7);

        $this->assertEquals('Atualizado', $response);
        $this->assertIsString($response);
        $this->seeInDatabase('carts_products', [
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 7
        ]);
    }

    /**
     * @throws JsonException
     */
    public function testAddProductWithoutCartModel(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Carrinho não encontrado!');
        $this->expectExceptionCode(400);

        $product = $this->seed(\ProductFactory::class)->create();

        $this->cartModel->addProduct(123, $product['id'], 1);
    }

    /**
     * @throws JsonException
     */
    public function testAddProductWithoutProductModel(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Produto não encontrado!');
        $this->expectExceptionCode(400);

        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $this->cartModel->addProduct($cart['id'], 123, 1);
    }

    /**
     * @throws JsonException
     */
    public function testRemoveProductExistsInCartModel(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create();

        $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 4
        ]);

        $response = $this->cartModel->removeProduct($cart['id'], $product['id']);

        $this->assertEquals('Deletado', $response);
        $this->assertIsString($response);
        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 4
        ]);
    }

    /**
     * @throws JsonException
     */
    public function testRemoveProductNotExistsInCartModel(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->cartModel->removeProduct($cart['id'], $product['id']);

        $this->assertEquals('Deletado', $response);
        $this->assertIsString($response);
        $this->seeNotInDatabase('carts_products', [
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 4
        ]);
    }

    /**
     * @throws JsonException
     */
    public function testRemoveProductWithoutCartModel(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Carrinho não encontrado!');
        $this->expectExceptionCode(400);

        $product = $this->seed(\ProductFactory::class)->create();

        $this->cartModel->removeProduct(123, $product['id']);
    }

    /**
     * @throws JsonException
     */
    public function testGetByIdWithProductsModel(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product1 = $this->seed(\ProductFactory::class)->create();
        $product2 = $this->seed(\ProductFactory::class)->create();

        $cartProduct1 = $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product1['id'],
            'product_quantity' => 4
        ]);

        $cartProduct2 = $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product2['id'],
            'product_quantity' => 2
        ]);

        $response = $this->cartModel->getByIdWithProducts($cart['id']);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('cart_id', $response[0]);
        $this->assertArrayHasKey('product_id', $response[0]);
        $this->assertArrayHasKey('product_name', $response[0]);
        $this->assertArrayHasKey('product_value', $response[0]);
        $this->assertArrayHasKey('product_quantity', $response[0]);
        $this->assertArrayHasKey('cart_id', $response[1]);
        $this->assertArrayHasKey('product_id', $response[1]);
        $this->assertArrayHasKey('product_name', $response[1]);
        $this->assertArrayHasKey('product_value', $response[1]);
        $this->assertArrayHasKey('product_quantity', $response[1]);

        $this->assertEquals($cartProduct1['cart_id'], $response[0]['cart_id']);
        $this->assertEquals($cartProduct1['product_id'], $response[0]['product_id']);
        $this->assertEquals($cartProduct1['product_quantity'], $response[0]['product_quantity']);
        $this->assertEquals($product1['name'], $response[0]['product_name']);
        $this->assertEquals($product1['value'], $response[0]['product_value']);

        $this->assertEquals($cartProduct2['cart_id'], $response[1]['cart_id']);
        $this->assertEquals($cartProduct2['product_id'], $response[1]['product_id']);
        $this->assertEquals($cartProduct2['product_quantity'], $response[1]['product_quantity']);
        $this->assertEquals($product2['name'], $response[1]['product_name']);
        $this->assertEquals($product2['value'], $response[1]['product_value']);
    }

    /**
     * @throws JsonException
     */
    public function testGetProductByCartModel(): void
    {
        $cart = $this->seed(\CartFactory::class)->create([
            'user_id' => $this->user['id']
        ]);

        $product = $this->seed(\ProductFactory::class)->create();

        $cartProduct = $this->seed(\CartProductFactory::class)->create([
            'cart_id' => $cart['id'],
            'product_id' => $product['id'],
            'product_quantity' => 4
        ]);

        $response = $this->cartModel->getProductByCart($cart['id'], $product['id']);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('cart_id', $response);
        $this->assertArrayHasKey('product_id', $response);
        $this->assertArrayHasKey('product_quantity', $response);

        $this->assertEquals($cartProduct['cart_id'], $response['cart_id']);
        $this->assertEquals($cartProduct['product_id'], $response['product_id']);
        $this->assertEquals($cartProduct['product_quantity'], $response['product_quantity']);
    }

    /**
     * @throws JsonException
     */
    public function testGetProductByCartWithoutCartModel(): void
    {
        $product = $this->seed(\ProductFactory::class)->create();

        $response = $this->cartModel->getProductByCart(123, $product['id']);
        $this->assertIsArray($response);
        $this->assertEquals([], $response);
    }

    public function testSumAllProductsModel(): void
    {
        $products[0]['product_value'] = 150;
        $products[0]['product_quantity'] = 2;
        $products[1]['product_value'] = 120;
        $products[1]['product_quantity'] = 3;

        $class = new \ReflectionClass($this->cartModel);
        $method = $class->getMethod('sumAllProducts');
        $method->setAccessible(true);
        $response = $method->invoke($this->cartModel, $products);

        $this->assertIsNumeric($response);
        $this->assertEquals(660, $response);
    }

    public function testSumAllProductsWithoutProductsModel(): void
    {
        $class = new \ReflectionClass($this->cartModel);
        $method = $class->getMethod('sumAllProducts');
        $method->setAccessible(true);
        $response = $method->invoke($this->cartModel, []);

        $this->assertIsNumeric($response);
        $this->assertEquals(0, $response);
    }
}