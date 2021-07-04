<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/5/21
 * Time: 10:01 PM
 */


namespace App\Contracts\Repositories;


interface PasswordResetRepository
{
    public function getPasswordResetData($token, int $company_id, $email);
}
