<?php

namespace PhpStudy\Tests\Unit\Services;

use Generator;
use PhpStudy\Src\Services\Ex1Service;
use PhpStudy\Src\Services\Ex2Service;
use PhpStudy\Src\Services\Ex3Service;
use PHPUnit\Framework\TestCase;

class Ex3ServiceUTest extends TestCase
{
    private Ex3Service $ex3Service;

    public function setup(): void
    {
        parent::setUp();
        $this->ex3Service = new Ex3Service(new Ex1Service(), new Ex2Service);
    }

    /**
     * Method: traductWordToNumber()
     * @return Generator
     */
    public function getTraduceWordToNumber(): Generator
    {
        yield 'Palavra => Usuario' => [
            'word' => 'Usuario',
            'wordValue' => 130,
            'isPrime' => false,
            'isHappy' => true,
            'isMultipleBy3Or5' => true,
        ];

        yield 'Palavra => abca' => [
            'word' => 'abca',
            'wordValue' => 7,
            'isPrime' => true,
            'isHappy' => true,
            'isMultipleBy3Or5' => false,
        ];

        yield 'Palavra => eec' => [
            'word' => 'eec',
            'wordValue' => 13,
            'isPrime' => true,
            'isHappy' => true,
            'isMultipleBy3Or5' => false,
        ];

        yield 'Palavra => if' => [
            'word' => 'if',
            'wordValue' => 15,
            'isPrime' => false,
            'isHappy' => false,
            'isMultipleBy3Or5' => true,
        ];

        yield 'Palavra => 123' => [
            'word' => '123',
            'wordValue' => 0,
            'isPrime' => false,
            'isHappy' => false,
            'isMultipleBy3Or5' => false,
        ];
    }

    /**
     * Method: sumOfCorrespondingNumber()
     * @return Generator
     */
    public function getSumOfCorrespondingNumber(): Generator
    {
        yield 'Palavra => Objective' => [
            'word' => 'Objective',
            'result' => 117
        ];
    }

    /**
     * Teste exemplo do exercicio para a palavra parametrizada
     *
     * @dataProvider getTraduceWordToNumber()
     *
     * @param string $word
     * @param int $wordValue
     * @param bool $isPrime
     * @param bool $isHappy
     * @param bool $isMultipleBy3Or5
     * @return void
     */
    public function testWordValueAndPrimeAndHappyAndMultipleBy3Or5(
        string $word,
        int $wordValue,
        bool $isPrime,
        bool $isHappy,
        bool $isMultipleBy3Or5,
    ): void
    {
        $response = $this->ex3Service->traductWordToNumber($word);

        $this->assertArrayHasKey('word_value', $response);
        $this->assertArrayHasKey('is_prime', $response);
        $this->assertArrayHasKey('is_happy', $response);
        $this->assertArrayHasKey('is_multiple_by_3_or_5', $response);

        $this->assertEquals($wordValue, $response['word_value']);
        $this->assertEquals($isPrime, $response['is_prime']);
        $this->assertEquals($isHappy, $response['is_happy']);
        $this->assertEquals($isMultipleBy3Or5, $response['is_multiple_by_3_or_5']);

        $this->assertIsArray($response);

        $this->assertCount(4, $response);
    }

    /**
     * Teste para validacao correspondencia numerica para a palavra $word.
     *
     * @dataProvider getTraduceWordToNumber()
     *
     * @param string $word
     * @param int $result
     * @return void
     * @throws \ReflectionException
     */
    public function testSumOfCorrespondingNumber(
        string $word,
        int $result
    ): void
    {
        $class = new \ReflectionClass($this->ex3Service);
        $method = $class->getMethod('sumOfCorrespondingNumber');
        $method->setAccessible(true);
        $response = $method->invoke($this->ex3Service, $word);

        $this->assertEquals($result, $response);
    }
}