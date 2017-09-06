<?php

namespace True\Standards\Container;

interface ContainerAcessibleInterface
{
    /**
     * @param ContainerInterface $box
     *
     * @return mixed
     */
    public function __registerContainer(ContainerInterface $box);
}
