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

  public static function leaveLettersAndNumbers($str) {
//    $str = strtolower(str_replace(' ', '', $str));
    $str = str_replace(' ', '', $str);
    return preg_replace ('/[^a-zA-ZА-Яа-я0-9]/', '', $str);
  }

  public static function cutOnlyFirstRusWords($str) {
//    $str = preg_split('/([.,\s][\w])|([_,;:\/\(\{"])/', $str)[0];

//    $str = preg_replace('/(.*?)([А-Я])/u', '$2', $str);
//    $str = preg_replace('/( [^ .\-\/а-яА-Я]*?)([ .\-\/а-яА-Я]*)([^ .\-\/а-яА-Я]*?)/u', '$1', $str);

    $tmpArr = Array();
    preg_match('/[а-яА-Я](.*)/u', $str, $tmpArr);
    $str = empty($tmpArr) ? $str : $tmpArr[0];
    $str = preg_split('/([.,\-\/\s][a-zA-Z0-9])|([_,;:\(\{"])/', $str);
    $str = $str[0];
//    Functions::printr($str);

//    $str = preg_replace('/(([.,;:\-\s][a-zA-Z0-9])|([_,;:\(\{"]))(.*)/', '', $str);
    $str = preg_replace('/ +/', ' ', $str);
    $str = preg_replace('/( *)-( *)/', '-', $str);
    $str = preg_replace('/(( *)\.( *))|(\.)/', '. ', $str);
    return $str;
  }

  public static function replaceSameLettersEnToRus($str) {
    return strtr($str,
//      'aeopcyxABEHOPCTX', 'аеорсухАВЕНОРСТХ');
      Array(
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
      ));
  }
}
