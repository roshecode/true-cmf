<?php

return [
    'interfaces' => [
        'Twig_LoaderInterface' => 'Twig_Loader_Filesystem',
    ],
    'singletons' => [
        CORE_SERVICES.'FileSystem\FS'        => CORE_SERVICES.'FileSystem\FS', // TODO: FS interface
        'Truth\Support\Interfaces\ViewInterface'      => CORE_SERVICES . 'View\Twig',
    ],
    'mutables' => [
        CORE_SERVICES . 'Multilingual\Lang'      => CORE_SERVICES . 'Multilingual\Lang', // TODO: Lang interface
    ],
    'aliases' => [
        'FS' => CORE_SERVICES.'FileSystem\FS',
        'Lang' => CORE_SERVICES . 'Multilingual\Lang',
        'View' => 'Truth\Support\Interfaces\ViewInterface'
    ]
];
