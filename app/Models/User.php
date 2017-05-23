<?php
namespace App\Models;

use T\Abstracts\Model;

/**
 * Class User
 * @package App\Models
 *
 * @property string $slug
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $login
 * @property string $password
 * @property bool $active
 */
class User extends Model
{
    protected $collection = 'users';
}
