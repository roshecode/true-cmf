<?php

namespace Tools;

class Functions {
  public static function correctEnd($val, $zero, $one, $two) {
    $result = $val;
    switch($val % 10) {
      case 1: $result = $result . $one; break;
      case 2:case 3:case 4: $result = $result . $two; break;
      default: $result = $result . $zero;
    }
    return $result;
  }

  public static function printr($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>' . PHP_EOL;
  }

  public static function replaceEnToRus($str) {
    return strtr(preg_split('/(\s[a-zA-Z])|([,;:])/', $str)[0],
//      'aeopcyxABEHOPCTX', 'аеорсухАВЕНОРСТХ');
      [
        'a' => 'а',
        'c' => 'с',
        'e' => 'е',
        'o' => 'о',
        'p' => 'р',
        'x' => 'х',
        'y' => 'у',
        'A' => 'А',
        'B' => 'В',
        'C' => 'С',
        'E' => 'Е',
        'H' => 'Н',
        'O' => 'О',
        'P' => 'Р',
        'T' => 'Т',
        'X' => 'Х',
      ]);
  }
}
