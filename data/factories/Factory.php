<?php

use PhpStudy\Src\Entities\Entity;

abstract class Factory extends Entity
{
    protected string $table = '';

    protected array $columns = [];

    protected \Faker\Generator $faker;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->initFaker();
    }

    /**
     * Cria faker inicial
     *
     * @return void
     */
    private function initFaker(): void
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Colunas do factory
     *
     * @return array
     */
    abstract public function columns(): array;

    /**
     * Salva um factory
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function create(array $data = []): array
    {
        $fieldsToSave = array_merge($this->columns(), $data);
        $id = $this->save($fieldsToSave);

        return array_merge(['id' => $id], $fieldsToSave);
    }
}