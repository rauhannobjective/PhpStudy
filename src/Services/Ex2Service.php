<?php

namespace PhpStudy\Src\Services;

class Ex2Service
{
    /**
     * Recebe um numero inteiro positivo e verifica se ele é feliz (true) ou não (false).
     *
     * @param integer $number
     * @return bool
     */
    public function determinesHappyNumber(int $number): bool
    {
        $arrayNumbers = [];
        $result = true;
        $number = abs($number);

        while ($number != 1) {
            if (in_array($number, $arrayNumbers)) {
                $result = false;
                break;
            }

            $arrayNumbers[] = $number;
            $number = $this->sumOfSquares($number);
        }

        return $result;
    }

    /**
     * Retorna a soma dos quadrados dos algarismos de um numero inteiro.
     *
     * @param integer $number
     * @return integer
     */
    private function sumOfSquares(int $number): int
    {
        $sum = 0;

        for ($i = 0; $i < strlen($number); $i++) {
            $sum += pow((int)substr($number, $i, 1), 2);
        }

        return $sum;
    }
}