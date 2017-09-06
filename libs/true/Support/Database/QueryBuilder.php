<?php

namespace True\Support\Database;

class QueryBuilder
{
    protected $queryPieces = [];
    protected $values;

    public function flatten(array $array) {
        // Convert two dimensional array into one dimensional array
        return array_reduce($array, function ($carry, $item) {
            return array_merge($carry, array_values($item));
        }, []);
        //call_user_func_array('array_merge', $data); //array_map('current', $data)
    }

    public function isAssoc(array $array)
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Get column names
     *
     * @param $data
     *
     * @return string
     */
    protected function columns($data)
    {
        return '(' . implode(',', array_keys($data[0])) . ')';
    }

    protected function values($data)
    {
        $this->values = $this->flatten($data);
        $placeholders = '(?' . str_repeat(',?', count($data[0]) - 1) . ')';

        return $placeholders . str_repeat(",$placeholders", count($data) - 1);
    }

    public function insert($data)
    {
        if (empty($data)) {
            return true;
        }

        // If data is not a collection
        if (! is_array(reset($data))) {
            $data = [$data];
        }

        $this->queryPieces[0] = 'INSERT INTO';
        $this->queryPieces[2] = "{$this->columns($data)} VALUES {$this->values($data)}";

        return $this;
    }

    public function into($table)
    {
        $this->queryPieces[1] = $table;

        return $this;
    }

    public function select($columns = '*')
    {
        $this->queryPieces[0] = "SELECT $columns";

        return $this;
    }

    public function from($table)
    {
        $this->queryPieces[1] = "FROM `$table`";

        return $this;
    }
}
