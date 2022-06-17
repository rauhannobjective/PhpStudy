<?php

namespace PhpStudy\Tests\Unit\Services;

use Generator;
use PhpStudy\Src\Services\Ex1Service;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class Ex1ServiceUTest extends TestCase
{
    private Ex1Service $ex1Service;

    public function setup(): void
    {
        parent::setUp();
        $this->ex1Service = new Ex1Service();
    }

    /**
     * Method: sumMultiplesOfN1OrN2ForNaturalNumber()
     * @return Generator
     */
    public function getSumMultiplesOfN1OrN2ForNaturalNumber(): Generator
    {
        yield 'Intervalo 10 de 3 ou 5' => [
            'n1' => 3,
            'n2' => 5,
            'naturalNumber' => 10,
            'result' => 23
        ];

        yield 'Intervalo 1000 de 3 ou 5' => [
            'n1' => 3,
            'n2' => 5,
            'naturalNumber' => 1000,
            'result' => 233168
        ];

        yield 'Intervalo 0 de 3 ou 5' => [
            'n1' => 3,
            'n2' => 5,
            'naturalNumber' => 0,
            'result' => 0
        ];
    }

    /**
     * Method: sumMultiplesOfN1AndN2ForNaturalNumber()
     * @return Generator
     */
    public function getSumMultiplesOfN1AndN2ForNaturalNumber(): Generator
    {
        yield 'Intervalo 10 de 3 e 5' => [
            'n1' => 3,
            'n2' => 5,
            'naturalNumber' => 10,
            'result' => 0
        ];

        yield 'Intervalo 1000 de 3 e 5' => [
            'n1' => 3,
            'n2' => 5,
            'naturalNumber' => 1000,
            'result' => 33165
        ];

        yield 'Intervalo 0 de 3 e 5' => [
            'n1' => 3,
            'n2' => 5,
            'naturalNumber' => 0,
            'result' => 0
        ];
    }

    /**
     * Method: sumMultiplesOfN1OrN2AndN3ForNaturalNumber()
     * @return Generator
     */
    public function getSumMultiplesOfN1OrN2AndN3ForNaturalNumber(): Generator
    {
        yield 'Intervalo 10 de (3 ou 5) e 7' => [
            'n1' => 3,
            'n2' => 5,
            'n3' => 7,
            'naturalNumber' => 10,
            'result' => 0
        ];

        yield 'Intervalo 0 de (3 ou 5) e 7' => [
            'n1' => 3,
            'n2' => 5,
            'n3' => 7,
            'naturalNumber' => 0,
            'result' => 0
        ];

        yield 'Intervalo 1000 de (3 ou 5) e 7' => [
            'n1' => 3,
            'n2' => 5,
            'n3' => 7,
            'naturalNumber' => 1000,
            'result' => 33173
        ];
    }

    /**
     * Method: isNaturalMultiple()
     * @return Generator
     */
    public function getIsNaturalMultiple(): Generator
    {
        yield '5 é multiplo de 10' => [
            'number' => 10,
            'multiple' => 5,
            'result' => true
        ];

        yield '3 é multiplo de 10' => [
            'number' => 10,
            'multiple' => 3,
            'result' => false
        ];

        yield '0 é multiplo de 10' => [
            'number' => 10,
            'multiple' => 0,
            'result' => false
        ];
    }

    /**
     * Method: determineIfItIsMultipleOfN1OrN2()
     * @return Generator
     */
    public function getDetermineIfItIsMultipleOfN1OrN2(): Generator
    {
        yield '3 ou 5 são multiplos de 10' => [
            'number' => 10,
            'multiple1' => 3,
            'multiple2' => 5,
            'result' => true
        ];

        yield '3 ou 5 são multiplos de 12' => [
            'number' => 12,
            'multiple1' => 3,
            'multiple2' => 5,
            'result' => true
        ];

        yield '3 ou 5 são multiplos de 7' => [
            'number' => 7,
            'multiple1' => 3,
            'multiple2' => 5,
            'result' => false
        ];
    }

    /**
     * Method: determineIfItIsMultipleOfN1AndN2()
     * @return Generator
     */
    public function getDetermineIfItIsMultipleOfN1AndN2(): Generator
    {
        yield '3 e 5 são multiplos de 10' => [
            'number' => 10,
            'multiple1' => 3,
            'multiple2' => 5,
            'result' => false
        ];

        yield '3 e 5 são multiplos de 15' => [
            'number' => 15,
            'multiple1' => 3,
            'multiple2' => 5,
            'result' => true
        ];

        yield '3 é multiplo de 9' => [
            'number' => 9,
            'multiple1' => 3,
            'multiple2' => 1,
            'result' => true
        ];
    }

    /**
     * Soma de numeros de um intervalo até naturalNumber dos multiplos de n1 ou n2
     *
     * @dataProvider getSumMultiplesOfN1OrN2ForNaturalNumber()
     *
     * @param int $n1
     * @param int $n2
     * @param int $naturalNumber
     * @param int $result
     * @return void
     */
    public function testMultipleN1OrN2InNaturalNumber(
        int $n1,
        int $n2,
        int $naturalNumber,
        int $result
    ): void
    {
        $response = $this->ex1Service->sumMultiplesOfN1OrN2ForNaturalNumber($naturalNumber, $n1, $n2);

        $this->assertEquals($result, $response);
    }

    /**
     * Soma de numeros de um intervalo até naturalNumber dos multiplos de n1 e n2
     *
     * @dataProvider getSumMultiplesOfN1AndN2ForNaturalNumber()
     *
     * @param int $n1
     * @param int $n2
     * @param int $naturalNumber
     * @param int $result
     * @return void
     */
    public function testMultipleN1AndN2InNaturalNumber(
        int $n1,
        int $n2,
        int $naturalNumber,
        int $result
    ): void
    {
        $response = $this->ex1Service->sumMultiplesOfN1AndN2ForNaturalNumber($naturalNumber, $n1, $n2);

        $this->assertEquals($result, $response);
    }

    /**
     * Soma de numeros de um intervalo até naturalNumber dos multiplos de (n1 ou n2) e n3
     *
     * @dataProvider getSumMultiplesOfN1OrN2AndN3ForNaturalNumber()
     *
     * @param int $n1
     * @param int $n2
     * @param int $n3
     * @param int $naturalNumber
     * @param int $result
     * @return void
     */
    public function testMultiple3Or5And7In0(
        int $n1,
        int $n2,
        int $n3,
        int $naturalNumber,
        int $result
    ): void
    {
        $response = $this->ex1Service->sumMultiplesOfN1OrN2AndN3ForNaturalNumber($naturalNumber, $n1, $n2, $n3);

        $this->assertEquals($result, $response);
    }

    /**
     * Validacao de multiplos dos numeros number e multiple
     *
     * @dataProvider getIsNaturalMultiple()
     *
     * @param int $number
     * @param int $multiple
     * @param bool $result
     * @return void
     * @throws ReflectionException
     */
    public function testIsNaturalMultiple(
        int  $number,
        int  $multiple,
        bool $result
    ): void
    {
        $class = new \ReflectionClass($this->ex1Service);
        $method = $class->getMethod('isNaturalMultiple');
        $method->setAccessible(true);
        $response = $method->invoke($this->ex1Service, $number, $multiple);

        $this->assertEquals($result, $response);
    }

    /**
     * Teste se um numero é multiplo de n1 ou n2 através de número number
     *
     * @dataProvider getDetermineIfItIsMultipleOfN1OrN2()
     * @param int $number
     * @param int $multiple1
     * @param int $multiple2
     * @param bool $result
     * @return void
     */
    public function testMultipleN1OrN2WithNumber(
        int  $number,
        int  $multiple1,
        int  $multiple2,
        bool $result
    ): void
    {
        $response = $this->ex1Service->determineIfItIsMultipleOfN1OrN2($number, $multiple1, $multiple2);

        $this->assertEquals($result, $response);
    }

    /**
     * Teste se um numero é multiplo de n1 ou n2 através de número number
     *
     * @dataProvider getDetermineIfItIsMultipleOfN1AndN2()
     * @param int $number
     * @param int $multiple1
     * @param int $multiple2
     * @param bool $result
     * @return void
     */
    public function testMultipleN1AndN2WithNumber(
        int  $number,
        int  $multiple1,
        int  $multiple2,
        bool $result
    ): void
    {
        $response = $this->ex1Service->determineIfItIsMultipleOfN1AndN2($number, $multiple1, $multiple2);

        $this->assertEquals($result, $response);
    }
}