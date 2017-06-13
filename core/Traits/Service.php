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
     * @param Box $box
     *
     * @return Service
     */
    final public function __register(Box $box) {
        $this->box = $box;
        return $this;
    }
    
    public function __boot() { }
}
