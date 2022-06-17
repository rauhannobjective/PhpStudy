<?php

namespace PhpStudy\Src\Services;

class Ex1Service
{
    /**
     * Itera sobre um número natural e retorna a soma dos multiplos de acordo com a regra: ((n1 ou n2) e n3).
     *
     * @param integer $naturalNumber
     * @param integer $n1
     * @param integer $n2
     * @param int $n3
     * @return integer
     */
    public function sumMultiplesOfN1OrN2AndN3ForNaturalNumber(
        int $naturalNumber,
        int $n1,
        int $n2,
        int $n3
    ): int {
        $sum = 0;

        for ($i = 0; $i < abs($naturalNumber); $i++) {
            $isMultipleOr = $this->determineIfItIsMultipleOfN1OrN2($i, $n1, $n2);
            $isMultipleAnd = $this->determineIfItIsMultipleOfN1AndN2($i, $n3);

            if ($isMultipleOr && $isMultipleAnd) {
                $sum += $i;
            }
        }

        return $sum;
    }

    /**
     * Itera sobre um número natural e retorna a soma dos multiplos de acordo com a regra: (n1 e n2).
     *
     * @param integer $naturalNumber
     * @param integer $n1
     * @param integer $n2
     * @return integer
     */
    public function sumMultiplesOfN1AndN2ForNaturalNumber(
        int $naturalNumber,
        int $n1,
        int $n2
    ): int {
        $sum = 0;

        for ($i = 0; $i < abs($naturalNumber); $i++) {
            $isMultiple = $this->determineIfItIsMultipleOfN1AndN2($i, $n1, $n2);

            if ($isMultiple) {
                $sum += $i;
            }
        }

        return $sum;
    }

    /**
     * Itera sobre um número natural e retorna a soma dos multiplos de acordo com a regra: (n1 ou n2).
     *
     * @param integer $naturalNumber
     * @param integer $n1
     * @param integer $n2
     * @return integer
     */
    public function sumMultiplesOfN1OrN2ForNaturalNumber(
        int $naturalNumber,
        int $n1,
        int $n2
    ): int {
        $sum = 0;

        for ($i = 0; $i < abs($naturalNumber); $i++) {
            $isMultiple = $this->determineIfItIsMultipleOfN1OrN2($i, $n1, $n2);

            if ($isMultiple) {
                $sum += $i;
            }
        }

        return $sum;
    }

    /**
     * Determina se 2 números são multiplos de um numero. (n1 ou n2).
     *
     * @param integer $number
     * @param integer $multiple1
     * @param integer $multiple2
     * @return boolean
     */
    public function determineIfItIsMultipleOfN1OrN2(
        int $number,
        int $multiple1,
        int $multiple2
    ): bool {
        $result = false;

        $multipleN1 = $this->isNaturalMultiple($number, abs($multiple1));
        $multipleN2 = $this->isNaturalMultiple($number, abs($multiple2));

        if ($multipleN1 || $multipleN2) {
            $result = true;
        }

        return $result;
    }

    /**
     * Determina se 2 números são multiplos de um numero. (n1 e n2).
     *
     * @param integer $number
     * @param integer $multiple1
     * @param integer $multiple2
     * @return boolean
     */
    public function determineIfItIsMultipleOfN1AndN2(
        int $number,
        int $multiple1,
        int $multiple2 = 1
    ): bool {
        $result = false;

        $multipleN1 = $this->isNaturalMultiple($number, abs($multiple1));
        $multipleN2 = $this->isNaturalMultiple($number, abs($multiple2));

        if ($multipleN1 && $multipleN2) {
            $result = true;
        }

        return $result;
    }

    /**
     * Dado um numero inteiro, a função retorna true se for multiplo dele ou false caso não for.
     *
     * @param integer $number
     * @param integer $multiple
     * @return boolean
     */
    private function isNaturalMultiple(
        int $number,
        int $multiple
    ): bool {
        if (0 == $multiple) {
            return false;
        }

        return $number % $multiple == 0;
    }
}