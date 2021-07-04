<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/9/21
 * Time: 8:03 PM
 */


namespace App\Repositories;

use App\Contracts\Repositories\PasswordResetRepository;
use App\Models\PasswordReset;
use Carbon\Carbon;

class PasswordResetRepositoryEloquent extends BaseRepository implements PasswordResetRepository
{
    public function __construct(PasswordReset $model)
    {
        parent::__construct($model);
    }

    public function getPasswordResetData($token, int $company_id, $email)
    {
        return $this->model
            ->where('token', $token)
            ->where('company_id', $company_id)
            ->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHours(1))
            ->first();
    }
}
