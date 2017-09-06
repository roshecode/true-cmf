<?php

namespace True\Standards\Container;

/**
 * Enumeration of available scopes in config
 */
abstract class ScopeEnum
{
    const Instantiated = 0;
    const Disposable = 1;
    const Mutable = 2;
    const Single = 3;
}
