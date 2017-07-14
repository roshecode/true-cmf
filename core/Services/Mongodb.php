<?php

namespace T\Services;

use MongoDB\Client;
use T\Interfaces\DBInterface;
use T\Traits\Servant;

class Mongodb /*extends \Truecode\Filesystem\DB*/ implements DBInterface
{
    use Servant;

    protected $db;

    public function __construct()
    {
        $client = new Client(getenv('DATABASE_URI'));
        $this->db = $client->{getenv('DATABASE_NAME')};
    }

    public function __get($name) {
        return $this->db->$name;
    }
}
