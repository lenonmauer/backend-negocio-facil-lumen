<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cnpj implements Rule
{
    public function passes($attribute, $value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        $chars = substr($value, 0, 12);
        $chars = str_split($chars);
        $cnpjLength = 14;

        $d1 = self::calcCnpjLastDigit($chars);
        $d2 = self::calcCnpjLastDigit($chars, $d1);

        $finalValue = implode('', $chars) . $d1.$d2;

        for ($i=0; $i<=9; $i++) {
            if ($finalValue == str_pad('', $cnpjLength, $i)) {
                return false;
            }
        }

        return $finalValue == $value && strlen($value) == $cnpjLength;
    }

    public function message()
    {
        return 'The :attribute is not a valid zip code.';
    }

    private static function calcCnpjLastDigit($arrayChars, $d1=false)
    {
        $digit = 0;
        $mult = [5,4,3,2,9,8,7,6,5,4,3,2];

        if ($d1 !== false) {
            array_unshift($mult, 6);
            $arrayChars[] = $d1;
        }

        foreach ($arrayChars as $index=>$char) {
            $digit+= intval($char) * $mult[$index];
        }

        return ($digit % 11) < 2 ? 0 : 11-($digit % 11);
    }
}
