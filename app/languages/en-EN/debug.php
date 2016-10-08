<?php

return [
    'before' => '{{ filename }}({{ line }}): ',
    'after'  => ' Code {{ code }}',
    'exceptions' => [
        'UnreadableFile' => 'File {{ 0 }} you try to read is unreadable!',
        'FileNotFound' => 'File {{ 0 }} you try to open is not found!',
        'InvalidFile' => 'Passing file {{ 0 }} is invalid',
//        'InvalidArgument' => 'value "{{ 0 }}" {% param ? passed to "{{ param }}" parameter %} in "{{ function }}" function at position {{ position }} expected to be {{ expected }}, type {{ given }} given.',
        'InvalidArgument' => 'value "{{ 0 }}" passed to "{{ param }}" parameter in "{{ function }}" function at position {{ position }} expected to be {{ expected }}, type {{ given }} given.',
        'UnexpectedValue' => 'Passing value {{ given }} must be {{ expected }}!',
        'BadMethodCall' => 'Method {{ 0 }} does not exist',
        'Range' => "Value {{ 0 }} must be less then {{ 1 }} and more than {{ 2 }}"
    ],
    'notices' => [
        'KeyDoesNotExists' => 'Array key {{ 0 }} does not exist.',
    ]
];
