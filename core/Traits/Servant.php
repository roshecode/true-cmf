<?php
namespace T\Traits;

use T\Interfaces\BoxInterface;

trait Servant
{
    /**
     * @var BoxInterface $box
     */
    protected $box;
    
    /**
     * @param BoxInterface $box
     *
     * @return Servant
     */
    final public function __register(BoxInterface $box) {
        $this->box = $box;
        return $this;
    }
    
    public function __boot() { }
}
