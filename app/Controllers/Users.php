<?php

namespace App\Controllers;

use App\Models\User;
use T\Facades\Box;

class Users
{
    public function all() {
        $usersCursor = Box::make(User::class)->all();
        $users = [];
        foreach ($usersCursor as $user) {
            $users[] = $user;
        }
        return $users ? $users : 'There are no any user';
    }

    public function withSlug($slug) {
        $user = Box::make(User::class)->first(['slug' => $slug]);
        return $user ? $user : "There is no user with slug '$slug'";
    }
}
