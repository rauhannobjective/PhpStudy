<?php

namespace PhpStudy\Src\Interfaces;

interface Freight
{
    /**
     * Calcula o frete através do cep parametrizado.
     *
     * @param string $zipCode
     * @return float
     */
    public function calculateFreight(string $zipCode): float;
}