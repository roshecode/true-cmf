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
        $fs = new FS(BASEDIR . '/core/Configuration/');
        parent::__construct($fs, 'services.php');

        $box->instance('Box', $box);

        foreach ($this->data['interfaces'] as $abstract => $concrete) {
            $box->bind($abstract, $concrete);
        }
        foreach ($this->data['singletons'] as $abstract => $concrete) {
            $box->singleton($abstract, $concrete);
        }
        foreach ($this->data['settings'] as $abstract => $settings) {
            $box->make($abstract, $settings);
        }
    }
}