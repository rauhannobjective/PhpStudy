<?php

namespace PhpStudy\Src\Services;

use PhpStudy\Src\Interfaces\Freight;

class CorreiosService implements Freight
{
    const FREIGHT_DETERMINER_VALUE = 100;

    /**
     * Calcula o frete nos correios através do cep parametrizado.
     *
     * @param string $zipCode
     * @return float
     */
    public function calculateFreight(string $zipCode): float
    {
        // Chamada oficial da API dos Correios
        return 1000;
    }

    /**
     * Aplica frete de acordo com o valor minimo da variavel FREIGHT_DETERMINER_VALUE.
     *
     * @param float $total
     * @param string $zipCode
     * @return float
     */
    public function applyFreight(
        float  $total,
        string $zipCode
    ): float
    {
        if ($total < self::FREIGHT_DETERMINER_VALUE && $total > 0) {
            $total += $this->calculateFreight($zipCode);
        }

        return $total;
    }
}