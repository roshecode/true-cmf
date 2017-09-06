<?php

namespace True\Support\Eventfulness;

use True\Standards\Eventfulness\EventInterface;

class Event implements EventInterface
{
    /**
     * Get event name
     *
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|object
     */
    public function getTarget()
    {
        // TODO: Implement getTarget() method.
    }

    /**
     * Get parameters passed to the event
     *
     * @return array
     */
    public function getParams()
    {
        // TODO: Implement getParams() method.
    }

    /**
     * Get a single parameter by name
     *
     * @param  string $name
     *
     * @return mixed
     */
    public function getParam($name)
    {
        // TODO: Implement getParam() method.
    }

    /**
     * Set the event name
     *
     * @param  string $name
     *
     * @return void
     */
    public function setName($name)
    {
        // TODO: Implement setName() method.
    }

    /**
     * Set the event target
     *
     * @param  null|string|object $target
     *
     * @return void
     */
    public function setTarget($target)
    {
        // TODO: Implement setTarget() method.
    }

    /**
     * Set event parameters
     *
     * @param  array $params
     *
     * @return void
     */
    public function setParams(array $params)
    {
        // TODO: Implement setParams() method.
    }

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @param  bool $flag
     */
    public function stopPropagation($flag)
    {
        // TODO: Implement stopPropagation() method.
    }

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped()
    {
        // TODO: Implement isPropagationStopped() method.
    }
}
