<?php

namespace T\Services;

use MongoDB\Client;
use T\Interfaces\DBInterface;
use T\Traits\Servant;

class Mongodb /*extends \Truecode\Filesystem\DB*/ implements DBInterface
{
    use Servant;

    protected $db;

    public function __construct($uri, $databaseName)
    {
        $client = new Client($uri);
        $this->db = $client->$databaseName;
    }

    public function __get($name) {
        return $this->db->$name;
    }
}
