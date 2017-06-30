<?php
namespace T\Services;

use T\Interfaces\ViewInterface;
use T\Traits\Servant;

class View implements ViewInterface
{
    use Servant;

    /**
     * @var \League\Plates\Engine
     */
    protected $engine;

//    public function __construct(\League\Plates\Engine $engine, \League\Plates\Extension\Asset $asset) {
    public function __construct() {
//        $engine->loadExtension($asset);
//        $this->engine = $engine;
        $this->engine = new \League\Plates\Engine(BASEDIR . '/resources/views/static');
        $this->engine->loadExtension(new \League\Plates\Extension\Asset(BASEDIR . '/public'));
    }

    public function make($layout) {
        return $this->engine->make($layout);
    }

    public function render($layout, array $data = []) {
        return $this->engine->render($layout, $data);
    }
}
