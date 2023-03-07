<?php 
namespace App\Resources;
use App\Core\Support\QueryBuilder;
class UsersResource
{
    public static function make($users)
    {
        $users_resource = array_map(function($user) {
            return [
                'Name' => $user->name,
                'Email' => $user->email,
                'Last Login' => $user->last_login,
                'Created At' => $user->created_at,
            ];
        }, $users);

        return $users_resource;
    }
}