<?php

namespace True\Routing;


class RouteNode
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var RouteNode
     */
    protected $parent;
    /**
     * @var array
     */
    protected $children;

    public function __construct($name, $parent = null, $method = null)
    {
        $this->name = $name;
        $this->method = $method;
        $this->parent = $parent;
    }

    public function getName() {
        return $this->name;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function addChild($routeNode) {
        $this->children[$routeNode->getName()] = $routeNode;
    }
}