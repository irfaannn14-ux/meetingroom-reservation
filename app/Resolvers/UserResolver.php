<?php

namespace App\Resolvers;

use OwenIt\Auditing\Contracts\UserResolver as UserResolverContract;
use App\Models\User;

class UserResolver implements UserResolverContract
{
    /**
     * Resolve the User.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function resolve()
    {
        // Custom auth implementation based on session('user_id')
        if (session()->has('user_id')) {
            return User::find(session('user_id'));
        }

        return null;
    }
}
