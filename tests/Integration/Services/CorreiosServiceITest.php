<?php

namespace PhpStudy\Tests\Integration\Services;

use PhpStudy\Src\Services\CorreiosService;
use PhpStudy\Tests\TestCase;

class CorreiosServiceITest extends TestCase
{
    const ZIPCODES = [
        '37500000'
    ];

    private CorreiosService $correiosService;

    public function setUp(): void
    {
        parent::setUp();
        $this->correiosService = new CorreiosService();
    }

    public function testCalculateFreight(): void
    {
        $response = $this->correiosService->calculateFreight(self::ZIPCODES[0]);

        // 1000 = retorno da integração.
        $this->assertEquals(1000, $response);
    }
}