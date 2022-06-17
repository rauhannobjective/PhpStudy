<?php

namespace PhpStudy\Src\Services;

use PhpStudy\Src\Traits\Numbers;
use PhpStudy\Src\Traits\Strings;
use PhpStudy\Src\Traits\Translator;

class Ex3Service
{
    use Translator, Strings, Numbers;

    private Ex1Service $ex1Service;
    private Ex2Service $ex2Service;

    public function __construct(
        Ex1Service $ex1Service,
        Ex2Service $ex2Service
    ) {
        $this->ex1Service = $ex1Service;
        $this->ex2Service = $ex2Service;
    }

    /**
     * Recebe uma string e lê os alfa-numéricos. Através disso, retorna elementos como: se é primo, seu valor correspondente, se é feliz e se é multiplo de 3 ou 5.
     *
     * @param string $word
     * @return array
     */
    public function traductWordToNumber(string $word): array
    {
        $string = preg_replace("/[^a-zA-Z]/", "", $this->removeAccents($word));

        if ($string != "") {
            $number = $this->sumOfCorrespondingNumber($string);
            $isPrime = $this->isPrime($number);
            $isHappy = $this->ex2Service->determinesHappyNumber($number);
            $isMultipleBy3Or5 = $this->ex1Service->determineIfItIsMultipleOfN1OrN2($number, 3, 5);
        }

        return [
            'word_value' => $number ?? 0,
            'is_prime' => $isPrime ?? false,
            'is_happy' => $isHappy ?? false,
            'is_multiple_by_3_or_5' => $isMultipleBy3Or5 ?? false
        ];
    }

    /**
     * Retorna a soma dos numeros mapeados por cada letra.
     *
     * @param string $word
     * @return integer
     */
    private function sumOfCorrespondingNumber(string $word): int
    {
        $sum = 0;

        for ($i = 0; $i < strlen($word); $i++) {
            $letter = substr($word, $i, 1);
            $sum += $this->letterToNumber($letter);
        }

        return $sum;
    }
}