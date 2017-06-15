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
        Box::make(\T\Services\Response::class)->headers->set('Content-Type', 'application/json');
        return $users ? json_encode($users) : 'There are no any user';
    }

    public function withSlug($slug) {
        $user = Box::make(User::class)->first(['slug' => $slug]);
        Box::make(\T\Services\Response::class)->headers->set('Content-Type', 'application/json');
        return $user ? json_encode($user) : "There is no user with slug '$slug'";
    }
}
