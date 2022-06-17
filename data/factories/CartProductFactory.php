<?php

class CartProductFactory extends Factory
{
    protected string $table = 'carts_products';

    public function columns(): array
    {
        return [
            'cart_id' => 1,
            'product_id' => 1,
            'product_quantity' => 1
        ];
    }
}