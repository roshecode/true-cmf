<?php

function d($v) {
    ?><pre><?php
    var_dump($v);
    ?></pre><br /><?php
}

function dd($v) {
    ?><pre><?php
    var_dump($v);
    ?></pre><br /><?php
    exit;
}
