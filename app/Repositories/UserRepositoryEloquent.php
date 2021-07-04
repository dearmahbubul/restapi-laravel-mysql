<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/7/21
 * Time: 8:03 PM
 */


namespace App\Repositories;


use App\Contracts\Repositories\UserRepository;
use App\Models\User;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
