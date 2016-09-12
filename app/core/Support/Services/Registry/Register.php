<?php

namespace Truth\Support\Services\Registry;

use Truth\Support\Services\FileSystem\FS;
use Truth\Support\Services\Locator\Box;
use Truth\Support\Services\Repository\FileRepository;

class Register extends FileRepository
{
    /**
     * Register constructor.
     * @param Box $box
     */
    public function __construct(Box $box)
    {
        $fs = new FS(COREDIR . 'Configuration');
        parent::__construct($fs, 'services.php');
//        dd($box->make('Truth\Support\Services\Repository\FileRepository', [BASEDIR . '/core/Configuration/', 'services.php']));

        $box->instance('Box', $box);

        $box->bind('Truth\Support\Services\FileSystem\FS');

        foreach ($this->data['interfaces'] as $abstract => $concrete) {
            $box->bind($abstract, $concrete);
        }
        foreach ($this->data['singletons'] as $abstract => $concrete) {
            $box->singleton($abstract, $concrete);
        }
        foreach ($this->data['mutables'] as $abstract => $concrete) {
            $box->mutable($abstract, $concrete);
        }
        foreach ($this->data['settings'] as $abstract => $settings) {
            $box->make($abstract, $settings);
        }
    }
}