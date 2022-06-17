<?php

namespace PhpStudy\Tests\Mocks;

use PhpStudy\Src\Services\CorreiosService;

class MockCorreiosService extends CorreiosService
{
    /**
     * Mock
     * @param string $zipCode
     * @return float
     */
    public function calculateFreight(string $zipCode): float
    {
        return 3;
    }
}