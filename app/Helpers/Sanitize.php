<?php
namespace App\Helpers;

class Sanitize {
  /**
   * Remove os caracteres não numéricos.
   * Se $parseInt == true, converte o valor para int.
   */
  public static function number($value, $parseInt=true) {
    $replacedValue = preg_replace('/[^0-9]/', '', $value);

    if($parseInt) {
      $replacedValue = intval($replacedValue);
    }

    return $replacedValue;
  }

  /**
   * Remove os caracteres não numéricos e substitui a virgula por ponto.
   * Se $parseFloat == true, converte o valor para float.
   */
  public static function float($value, $parseFloat=true) {
    $replacedValue = str_replace('.', '', $value);
    $replacedValue = str_replace(',', '.', $replacedValue);

    if($parseFloat) {
      $replacedValue = floatval($replacedValue);
    }

    return $replacedValue;
  }

  public static function replaceSpecialChars($string) {
    $unwanted_array = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ' ' => '', '_' => '', '-' => '', '.'=> '', ',' => '', ';' => ''];

    return strtr($string, $unwanted_array);
  }

  public static function arr($array) {
    return !empty($array) && is_array($array) ? $array : [];
  }

  public static function notEmptyOrNull($value, $allowZero=false) {
    if($allowZero) {
      return !empty($value) || ($value === 0 || $value === '0') ? $value : null;
    }
    else {
      return !empty($value) ? $value : null;
    }
  }
}