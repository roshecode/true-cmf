<?php

namespace Truth\Support\Services\Registry;

use Truth\Support\Abstracts\ServiceProvider;

class Register
{
    /**
     * Register constructor.
     * @param \Truth\Support\Services\Locator\Box $box
     */
    public function __construct($box)
    {
        $box->instance('Box', $box);

        $box->singleton('FS', ServiceProvider::CORE_SERVICES . '\\FileSystem\\FS'); $box->make('FS', [BASEDIR]);
        $box->singleton('Lang', ServiceProvider::CORE_SERVICES . '\\Multilingual\\Lang');
        $box->singleton('Config', ServiceProvider::CORE_SERVICES . '\\Configuration\\Config');
        $box->make('Config', [BASEDIR . '/core/Configuration', '/main.php']);
        $box->bind('Twig_LoaderInterface', 'Twig_Loader_Filesystem');
        $box->singleton('View', ServiceProvider::CORE_SERVICES . '\View\Twig');
        $box->make('View', [BASEDIR . '/core/Themes',
            ['cache' => BASEDIR . '/cache/themes', 'debug' => true, 'auto_reload' => true]]);
    }
}