<?php

namespace Truth\Support\Services\Routing;

class Route
{
    const KEY_REGEX         = 0;
    const KEY_NEXT          = 1;
    const KEY_PLACEHOLDERS  = ':';
    const KEY_MAKE          = '::';

    protected $name;
    protected $next;
    protected $placeholders;

    public function __construct($name, $regexWrapper = '/')
    {
        if ($name[0] === ':') {
            $parts_preg     = explode(':', $name, 3);
            $parts_filter   = explode('|', $parts_preg[1], 2);
            if (isset($parts_filter[1])) {
                $parts_preg[1] = $parts_filter[0];
                $regex = $this->filters[$parts_filter[1]];
            } elseif (isset($parts_preg[2])) {
                $regex = $parts_preg[2];
            } else {
                $regex = '';
            }
            $placeholder = &$this->placeholders[$parts_preg[1]];
            $placeholder = [self::KEY_REGEX => $regexWrapper . $regex . $regexWrapper];
            $this->next = &$placeholder[self::KEY_NEXT];
        } else {
            $this->next = &$this->placeholders[$name];
        }
    }

    public function &next() {
        return $this->next;
    }
}