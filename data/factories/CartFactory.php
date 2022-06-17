<?php

class CartFactory extends Factory
{
    protected string $table = 'carts';

    public function columns(): array
    {
        return [
            'user_id' => 2
        ];
    }
}