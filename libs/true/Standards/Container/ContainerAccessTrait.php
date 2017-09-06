<?php

namespace True\Standards\Container;

/**
 * Trait ContainerAccessTrait
 */
trait ContainerAccessTrait
{
    /**
     * The container instance
     *
     * @var ContainerInterface $box
     */
    private $container;

    /**
     * Register the container
     *
     * @param ContainerInterface $container
     */
    final public function __registerContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
