<?php

namespace Core\Services;

use MongoDB\Client;

class Mongodb /*extends \True\Support\Filesystem\DB*/ implements Contracts\DB
{
    protected $db;

    public function __construct()
    {
        $uri = "{${getEnv('DB_DRIVER')}}://{${getEnv('DB_HOST')}}:{${getEnv('DB_PORT')}}";
        $client = new Client($uri);
        $this->db = $client->{getenv('DA_NAME')};
    }

    public function __get($name) {
        return $this->db->$name;
    }

    public function select($columns = self::ALL)
    {
        // TODO: Implement select() method.
    }
}
