<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cpf implements Rule
{
    public function passes($attribute, $value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        $chars = substr($value, 0, 9);
        $chars = str_split($chars);
        $cpfLength = 11;

        $d1 = self::calcCpfLastDigit($chars);
        $d2 = self::calcCpfLastDigit($chars, $d1);

        $finalValue = implode('', $chars).$d1.$d2;

        for ($i=0; $i<=9; $i++) {
            if ($finalValue == str_pad('', $cpfLength, $i)) {
                return false;
            }
        }

        return $finalValue == $value && strlen($value) == $cpfLength;
    }

    public function message()
    {
        return 'The :attribute is not a valid zip code.';
    }

    private static function calcCpfLastDigit($arrayChars, $d1=false)
    {
        $digit = 0;
        $subIndex = 10;

        if ($d1 !== false) {
            $arrayChars[]= $d1;
            $subIndex++;
        }

        foreach ($arrayChars as $index=>$char) {
            $digit+= intval($char) * ($subIndex-$index);
        }

        return ($digit % 11) < 2 ? 0 : 11 - ($digit % 11);
    }
}
