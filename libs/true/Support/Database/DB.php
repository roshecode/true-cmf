<?php

namespace True\Support\Database;

use PDO;
use PDOException;

class DB
{
    /**
     * @var \PDO
     */
    protected $dbh;

    /**
     * @var \PDOStatement
     */
    protected $stmt;

    public function __construct(
        string $driver,
        string $host,
        string $name,
        string $username,
        string $password,
        string $charset = 'utf8'
    ) {
        try {
            $this->dbh = new PDO(
                "$driver:host=$host;dbname=$name;charset=$charset",
                $username,
                $password,
                [
                    PDO::ATTR_EMULATE_PREPARES  => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                ]
            );
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'FAILED TO GET DB HANDLE: >' . $e->getMessage();
        }
    }

    protected $queryPieces = [];
    protected $values;

//    protected function toSnakeCase($input) {
//        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
//        $ret = $matches[0];
//
//        foreach ($ret as &$match) {
//            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
//        }
//
//        return implode('_', $ret);
//    }

    protected function columns($data)
    {
        $keys = array_keys($data[0]);

//        foreach ($keys as &$key) {
//            $key = $this->toSnakeCase($key);
//        }

        return '(' . implode(',', $keys) . ')';
    }

    protected function values($data)
    {
        var_dump($this->getQuery());
        // Convert two dimensional array into one dimensional array
        $this->values = array_reduce($data, function ($carry, $item) {
            return array_merge($carry, array_values($item));
        }, []);
        //call_user_func_array('array_merge', $data); //array_map('current', $data)
        $placeholders = '(?' . str_repeat(',?', count($data[0]) - 1) . ')';

        return $placeholders . str_repeat(",$placeholders", count($data) - 1);
    }

    public function prepare($query)
    {
        $this->stmt = $this->dbh->prepare($query);

        return $this;
    }

    public function getQuery()
    {
        ksort($this->queryPieces);

        return implode(' ', $this->queryPieces);
    }

    public function execute()
    {
        $this->prepare($this->getQuery());

        // reset query pieces
        $this->queryPieces = [];

        return $this->stmt->execute($this->values);
    }

    public function fetch(string $classToBind)
    {
        $this->execute();

        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
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
