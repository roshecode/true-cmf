<?php

return [
    'interfaces' => [
        'Twig_LoaderInterface' => 'Twig_Loader_Filesystem',
    ],
    'singletons' => [
        'FS'        => CORE_SERVICES . 'FileSystem\FS',
        'View'      => CORE_SERVICES . 'View\Twig',
    ],
    'mutables' => [
        'Lang'      => CORE_SERVICES . 'Multilingual\Lang',
    ],
];
