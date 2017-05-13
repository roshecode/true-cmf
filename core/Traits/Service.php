<?php
namespace T\Traits;

use T\Interfaces\Box;

trait Service
{
    /**
     * @var Box $box
     */
    protected $box;
    
    /**
     * @param Box $container
     *
     * @return Service
     */
    final public function __register(Box $container) {
        $this->box = $container;
        return $this;
    }
    
    public function boot() { }
}
