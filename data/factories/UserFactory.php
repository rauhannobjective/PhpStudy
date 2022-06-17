<?php

class UserFactory extends Factory
{
    protected string $table = 'users';

    public function columns(): array
    {
        return [
            'name' => 'Objective',
            'cep' => '37500123'
        ];
    }
}