<?php

namespace PhpStudy\Src\Traits;

trait Numbers
{
    /**
     * Valida se numero é primo ou não.
     *
     * @param integer $number
     * @return boolean
     */
    public function isPrime(int $number): bool
    {
        if ($number == 1) {
            return false;
        }

        for ($i = 2; $i <= $number / 2; $i++) {
            if ($number % $i == 0) {
                return false;
            }
        }

        return true;
    }
}