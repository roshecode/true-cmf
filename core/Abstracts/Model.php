<?php

namespace T\Abstracts;

use T\Interfaces\DB;

abstract class Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @var \MongoDB\Collection $data
     */
    protected $data;

    /**
     * Collection name
     *
     * @var string $collection
     */
    protected $collection;

    /**
     * Model constructor.
     *
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->data = $db->{$this->collection};
    }

    /**
     * Get first model
     *
     * @param array $filter
     * @param array $options
     * @return array|null|object
     */
    public function first($filter = [], array $options = []) {
        return $this->data->findOne($filter, $options);
    }

    /**
     * Get all models
     *
     * @return \MongoDB\Driver\Cursor
     */
    public function all() {
        return $this->data->find();
    }

    /**
     * Find model by parameters
     *
     * @param array $filter
     * @param array $options
     * @return \MongoDB\Driver\Cursor
     */
    public function find($filter = [], array $options = []) {
        return $this->data->find($filter, $options);
    }

    public function update($filter, $update) {
        return $this->data->updateOne($filter, $update);
    }
}
