<?php

class Pagination implements \Extension\Module
{

    public function install()
    {
        // TODO: Implement install() method.
    }

    public function init()
    {
        \TF\Routing\Router::get('*/[Material]/page/{n}', 'Pagination@page');
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function page($n) {

    }
}
