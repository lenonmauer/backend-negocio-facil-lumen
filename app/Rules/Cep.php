<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cep implements Rule
{
    public function passes($attribute, $value)
    {
      $value = str_replace('-', '', $value);
      $match = preg_match('/[0-9]{8}$/', $value, $matches);

      return $match == 1 && $matches[0] == $value;
    }

    public function message()
    {
        return 'O valor do campo CEP é inválido.';
    }
}