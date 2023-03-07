<?php
namespace App\Controllers\Api;
use App\Core\Support\QueryBuilder;
use App\Resources\UsersResource;
class UserController extends API
{
    public function users()
    {
        self::checkAuthentication();
        $users = UsersResource::make(QueryBuilder::all('users'));
        self::response($users, "", TRUE, 200, TRUE);
    }  
}