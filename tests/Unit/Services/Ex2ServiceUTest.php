<?php

namespace PhpStudy\Tests\Unit\Services;

use Generator;
use PhpStudy\Src\Services\Ex2Service;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class Ex2ServiceUTest extends TestCase
{
    private Ex2Service $ex2Service;

    public function setup(): void
    {
        parent::setUp();
        $this->ex2Service = new Ex2Service();
    }

    /**
     * Method: determinesHappyNumber()
     * @return Generator
     */
    public function getDeterminesHappyNumber(): Generator
    {
        yield '7 é feliz?' => [
            'number' => 7,
            'result' => true
        ];

        yield '-7 é feliz?' => [
            'number' => -7,
            'result' => true
        ];

        yield '10 é feliz?' => [
            'number' => 10,
            'result' => true
        ];

        yield '2 é feliz?' => [
            'number' => 2,
            'result' => false
        ];

        yield '0 é feliz?' => [
            'number' => 0,
            'result' => false
        ];
    }

    /**
     * Method: sumOfSquares()
     * @return Generator
     */
    public function getSumOfSquares(): Generator
    {
        yield 'Soma dos quadrados de 7' => [
            'number' => 7,
            'result' => 49
        ];

        yield 'Soma dos quadrados de 16' => [
            'number' => 16,
            'result' => 37
        ];

        yield 'Soma dos quadrados de 83' => [
            'number' => 83,
            'result' => 73
        ];

        yield 'Soma dos quadrados de -43' => [
            'number' => -43,
            'result' => 25
        ];
    }

    /**
     * Verifica se um numero é feliz (true) ou nao (false)
     *
     * @dataProvider getDeterminesHappyNumber()
     *
     * @param int $number
     * @param bool $result
     * @return void
     */
    public function testHappyNumberN(
        int  $number,
        bool $result): void
    {
        $response = $this->ex2Service->determinesHappyNumber($number);

        $this->assertEquals($result, $response);
    }

    /**
     * Teste para validacao de soma dos quadrados de um numero N
     *
     * @dataProvider getSumOfSquares()
     *
     * @param int $number
     * @param int $result
     * @return void
     * @throws ReflectionException
     */
    public function testSumOfSquaresNTrue(
        int $number,
        int $result
    ): void
    {
        $class = new \ReflectionClass($this->ex2Service);
        $method = $class->getMethod('sumOfSquares');
        $method->setAccessible(true);
        $response = $method->invoke($this->ex2Service, $number);

        $this->assertEquals($result, $response);
    }
}