<?php

return [
    'prefix' => '{{ filename }}({{ line }}): ',
    'suffix' => ' Code {{ code }}',
    'exceptions' => [
        'UnreadableFile' => 'File %s you try to read is unreadable!',
        'FileNotFound' => 'File %s you try to open is not found!',
        'InvalidFile' => 'Passing file {0} is not {valid}',
//        'InvalidArgument' => 'Passing argument %s is invalid. %s expected.',
        'InvalidArgument' => 'value "{{ 0 }}" passed to "{{ function }}" function at position {{ num }} expected to be {{ valid }}, type {{ invalid }} given.',
        'UnexpectedValue' => 'Passing value %s must be %s!',
        'BadMethodCall' => 'Method %s does not exist',
        'Range' => "Value %s must be less then %d and more than %d"
    ],
    'notices' => [
        'key_does_not_exist' => 'Array key %s does not exist.',
    ]
];
