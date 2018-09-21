<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateBr implements Rule
{
    public function passes($attribute, $value)
    {
      $dateISO = explode('/', $value);
      $count = count($dateISO);
      $dateISO = array_reverse($dateISO);
      $dateISO = implode('-', $dateISO);

      return $count == 3 && self::isDate($dateISO);
    }

    private static function isDate($value) {
      $dateTime = strtotime($value);
      $day = date('d', $dateTime);
      $month = date('m', $dateTime);
      $year = intval(date('Y', $dateTime));

      return $dateTime && checkdate($month, $day, $year) && $year > 1900 && $year < 2100;
    }

    public function message()
    {
        return 'The :attribute is not a valid date.';
    }
}
