<?php

namespace PhpStudy\Tests\Unit\Services;

use Generator;
use PhpStudy\Tests\Mocks\MockCorreiosService;
use PHPUnit\Framework\TestCase;

class CorreiosServiceUTest extends TestCase
{
    private MockCorreiosService $correiosService;

    public function setup(): void
    {
        parent::setUp();
        $this->correiosService = new MockCorreiosService();
    }

    /**
     * Method: applyFreight()
     * @return Generator
     */
    public function getApplyFreight(): Generator
    {
        yield 'Com frete' => [
            'total' => 3,
            'zipcode' => '37500000',
            'result' => 6
        ];

        yield 'Sem frete' => [
            'total' => 300,
            'zipcode' => '37500000',
            'result' => 300
        ];

        yield 'Sem total' => [
            'total' => 0,
            'zipcode' => '37500000',
            'result' => 0
        ];
    }

    /**
     * @dataProvider getApplyFreight()
     *
     * @param float $total
     * @param string $zipcode
     * @param float $result
     * @return void
     */
    public function testApplyFreightWithoutFreightService(
        float $total,
        string $zipcode,
        float $result
    ): void
    {
        $response = $this->correiosService->applyFreight($total, $zipcode);

        $this->assertEquals($result, $response);
    }
}