<?php

namespace T\Abstracts;

use T\Interfaces\DB;

abstract class Model
{
    /**
     * @var \MongoDB\Collection
     */
    protected $data;
    protected $collection;

    public function __construct(DB $db)
    {
        $this->data = $db->{$this->collection};
    }

    public function first() {
        return $this->data->findOne();
    }

    public function find() {
        return $this->data->find(...func_get_args());
    }
}
