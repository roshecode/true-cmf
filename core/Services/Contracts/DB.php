<?php
namespace Core\Services\Contracts;

interface DB
{
    const ALL = '*';

    public function select($columns = self::ALL);
}
