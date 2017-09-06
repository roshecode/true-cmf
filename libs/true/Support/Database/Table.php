<?php

namespace True\Support\Database;

class Table
{
    /**
     * The table the blueprint describes.
     *
     * @var string
     */
    protected $table;

    /**
     * The columns that should be added to the table.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * The commands that should be run for the table.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * The storage engine that should be used for the table.
     *
     * @var string
     */
    public $engine;

    /**
     * The default character set that should be used for the table.
     */
    public $charset;

    /**
     * The collation that should be used for the table.
     */
    public $collation;

    /**
     * Whether to make the table temporary.
     *
     * @var bool
     */
    public $temporary = false;

    /**
     * Create a new schema blueprint.
     *
     * @param  string  $table
     * @param  \Closure|null  $callback
     */
    public function __construct($table, Closure $callback = null)
    {
        $this->table = $table;

        if (! is_null($callback)) {
            $callback($this);
        }
    }
}
