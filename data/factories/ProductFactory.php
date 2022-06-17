<?php

class ProductFactory extends Factory
{
    protected string $table = 'products';

    public function columns(): array
    {
        return [
            'name' => $this->faker->name,
            'value' => 10
        ];
    }
}