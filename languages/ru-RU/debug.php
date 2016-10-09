<?php

return [
    'before' => '{{ filename }}({{ line }}): ',
    'after'  => ' Code {{ code }}',
    'exceptions' => [
        'UnreadableFile' => 'File %s you try to read is unreadable!',
        'FileNotFound' => 'File %s you try to open is not found!',
        'InvalidFile' => 'Passing file {0} is not {valid}',
        'InvalidArgument' => 'значение "{{ 0 }}", переданное в параметре "{{ param }}" в функции "{{ function }}" на позиции {{ position }}, должно быть {{ expected }}, а не {{ given }}.',
        'UnexpectedValue' => 'Passing value %s must be %s!',
        'BadMethodCall' => 'Method %s does not exist',
        'Range' => "Value %s must be less then %d and more than %d"
    ],
    'notices' => [
        'key_does_not_exist' => 'Array key %s does not exist.',
    ]
];
