<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/5/21
 * Time: 8:06 PM
 */


namespace App\Contracts\Services;


use Illuminate\Http\Request;

interface UserAuthContract
{
    public function getByUserId(int $user_id);

    public function login(Request $request);

    public function logout();

    public function getLoggedUser();

    public function getRefreshUserToken();

    public function handleSignup(Request $request);

    public function handleForgetPassword(Request $request);

    public function handleResetPassword(Request $request);

    public function changePassword(Request $request, int $auth_user_id, int $user_id);

    public function updateUser(Request $request, int $auth_user_id, int $user_id);
}
