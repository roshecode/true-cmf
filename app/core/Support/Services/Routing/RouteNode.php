<?php

namespace Truth\Support\Services\Routing;

class RouteNode
{
    const NODES_STATIC = 0;
    const NODES_DYNAMIC = 0;

    /**
     * @var array $children
     */
    protected $children;
    protected $regexp;
    protected $make;

    public function &add($name, $regexWrapper = '/') {
        if ($name[0] === ':') {
            $parts_preg     = explode(':', $name, 3);
            $parts_filter   = explode('|', $parts_preg[1], 2);
            if (isset($parts_filter[1])) {
                $parts_preg[1] = $parts_filter[0];
                $regex = Router::getFilter($parts_filter[1]);
            } elseif (isset($parts_preg[2])) {
                $regex = $parts_preg[2];
            } else {
                $regex = '';
            }
            $placeholder = &$this->children[self::NODES_DYNAMIC][$parts_preg[1]];
            if (! $placeholder) $placeholder = new self;
            $placeholder->setMatch($regexWrapper . $regex . $regexWrapper);
            return $placeholder;
        } else {
            $placeholder = &$this->children[self::NODES_STATIC][$name];
            if (! $placeholder) $placeholder = new self;
            return $placeholder;
        }
    }

    public function &match(&$value, &$params) {
        if (isset($this->children[self::NODES_STATIC][$value])) { // try to match static node
            return $this->children[self::NODES_STATIC][$value];
        } elseif (isset($this->children[self::NODES_DYNAMIC])) { // else try to match by regexp
            foreach ($this->children[self::NODES_DYNAMIC] as $nodeName => $dynamicNode) { // TODO: backward
                /** @var self $dynamicNode */
                if ($dynamicNode->isMatched($value)) {
                    $params[$nodeName] = $value;
                    return $dynamicNode;
                }
            }
            throw new \Exception('404'); // if nothing matched
        } else throw new \Exception('404'); // if nothing matched throw exception
    }

    public function setMatch($regexp) {
        $this->regexp = $regexp;
    }

    public function isMatched(&$value) {
        return preg_match($this->regexp, $value);
    }

    /**
     * @param callable|string $make
     * @throws \Exception
     */
    public function setMake($make) {
        $this->make = is_string($make) ? explode('@', $make, 2) : $make;
    }

    public function make($params) {
//        if (isset($this->pointer) && is_callable($this->pointer)) {
        if (is_callable($this->make)) {
            call_user_func_array($this->make, $params);
        } else throw new \Exception('Route must be a string or callable');
    }
}
