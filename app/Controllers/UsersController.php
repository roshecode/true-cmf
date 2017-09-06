<?php

namespace App\Controllers;

use App\Models\User;
use Core\Services\Facades\App;
use Core\Services\Response;

class UsersController
{
    public function all() {
        $usersCursor = App::make(User::class)->all();
        $users = [];
        foreach ($usersCursor as $user) {
            $users[] = $user;
        }
        return $users ? $users : 'There are no any user';
    }

    public function withSlug($slug) {
        $user = App::make(User::class)->first(['slug' => $slug]);
        return $user ? $user : "There is no user with slug '$slug'";
    }

    public function post($slug) {
        $users = App::make(User::class);
        $user = $users->first(['slug' => $slug]);
        return $user ? $users->update(
            ['slug' => $slug],
            ['$set' => App::make(\Psr\Http\Message\ServerRequestInterface::class)->getParsedBody()]
        ) : "There is no user with slug '$slug'";
    }
}
