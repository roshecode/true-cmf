<?php

function first(&$array) {
//    if (!is_array($array)) return $array;
    if (!count($array)) return null;
    reset($array);
    return $array[key($array)];
}

function last(&$array) {
//    if (!is_array($array)) return $array;
    if (!count($array)) return null;
    end($array);
    return $array[key($array)];
}

function base_path() {

}

function d($v1, $v2 = null) {
    $v = ($v2 === null ? $v1 : $v2);
    $func = (is_string($v) || is_numeric($v) ? 'print_r' : 'var_dump');
    ?><pre><?php
    if ($v2 !== null) echo $v1;
    $func($v);
    ?></pre><hr /><?php
}

function dd($v) {
    d($v);
    exit;
}
