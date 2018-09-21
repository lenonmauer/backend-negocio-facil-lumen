<?php
namespace App\Helpers;

use App\Helpers\Sanitize;

class Format {

  /**
   * Converte uma data 'dd/mm/YYYY' para 'YYYY-mm-dd'.
   */
  public static function dateBRToISO($value) {
    $date = explode('/', $value);
    $date = array_reverse($date);
    $date = implode('-', $date);

    return $date;
  }

  /**
   * Converte uma data 'YYYY-mm-dd' para 'dd/mm/YYYY'.
   */
  public static function dateISOToBR($value) {
    $date = explode('-', $value);
    $date = array_reverse($date);
    $date = implode('/', $date);

    return $date;
  }

  /**
   * Converte uma data 'dd/mm/YYYY HH:ii' para 'YYYY-mm-dd HH:ii'.
   */
  public static function datetimeBRToISO($value) {
    $split = explode(' ', $value);

    if( count($split) == 2 ) {
      $date = self::dateBRToISO($split[0]);
      $time = $split[1];

      return $date.' '.$time;
    } else {
      return null;
    }
  }

  /**
   * Converte uma data 'YYYY-mm-dd HH:ii' para 'dd/mm/YYYY HH:ii'.
   */
  public static function datetimeISOToBR($value, $seconds=false) {
    $split = explode(' ', $value);

    if( count($split) == 2 ) {
      $date = self::dateISOToBR($split[0]);
      $time = $split[1];

      if($seconds == false && strlen($time) == 8) {
        $time = substr($time, 0, 5);
      }

      return $date.' '.$time;
    } else {
      return null;
    }
  }

  /**
   * Formata a string como CPF.
   */
  public static function stringToCPF($value) {
    $replacedValue = preg_replace('/[^0-9]/', '', $value);
    $pt1 = substr($replacedValue, 0, 3);
    $pt2 = substr($replacedValue, 3, 3);
    $pt3 = substr($replacedValue, 6, 3);
    $pt4 = substr($replacedValue, 9);

    return $pt1.'.'.$pt2.'.'.$pt3.'-'.$pt4;
  }

  public static function stringToCNPJ($value) {
    $replacedValue = preg_replace('/[^0-9]/', '', $value);
    $pt1 = substr($replacedValue, 0, 2);
    $pt2 = substr($replacedValue, 2, 3);
    $pt3 = substr($replacedValue, 5, 3);
    $pt4 = substr($replacedValue, 8, 4);
    $pt5 = substr($replacedValue, 12);

    return $pt1.'.'.$pt2.'.'.$pt3.'/'.$pt4.'-'.$pt5;
  }

  public static function stringToCEP($value) {
    $replacedValue = preg_replace('/[^0-9]/', '', $value);
    $pt1 = substr($replacedValue, 0, 5);
    $pt2 = substr($replacedValue, 5);

    return $pt1.'-'.$pt2;
  }

  public static function floatToCurrency($value) {
    return number_format($value, 2, ',', '.');
  }

  public static function currencyToFloat($value) {
    return Sanitize::float($value);
  }

}