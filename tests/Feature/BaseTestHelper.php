<?php
/**
 * Created by Mahbubul Alam
 * Date: 4/21/21
 * Time: 4:18 PM
 */
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class BaseTestHelper extends TestCase
{
    public function deleteUser($userId)
    {
        User::where('id',$userId)->delete();
    }

    protected function getLoggedInUser()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);
        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => $password,
            'company_id' => $user->company_id
        ]);
        return $response->json('payload');
    }

    protected function getAccessToken()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);
        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => $password,
            'company_id' => $user->company_id
        ]);
        $payload = $response->json('payload');
        return $payload['token']['access_token'];
    }
}
