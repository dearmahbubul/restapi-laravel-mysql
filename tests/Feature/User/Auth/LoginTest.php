<?php
/**
 * Created by Mahbubul Alam
 * User: Masakh Technology
 * Date: 4/9/21
 * Time: 8:06 PM
 */

namespace Tests\Feature\User\Auth;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\BaseTestHelper;

class LoginTest extends BaseTestHelper
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider EmailInputValidation
     * @dataProvider passwordInputValidation
     * @dataProvider companyInputValidation
     */
    public function is_validates_login_credentials($form_input, $form_input_value)
    {
        $response = $this->post(route('user.login'), [$form_input => $form_input_value]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($form_input,'payload');
    }

    public function EmailInputValidation() : array
    {
        return [
            ['email', ''],
            ['email', 'lorem'],
            ['email', 'lorem-ipsum']
        ];
    }

    public function passwordInputValidation() : array
    {
        return [
            ['password', ''],
            ['password', 'lorem'],
        ];
    }

    public function companyInputValidation() : array
    {
        return [
            ['company_id', ''],
        ];
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);
        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => $password,
            'company_id' => $user->company_id,
        ]);
        $response->assertSuccessful();
        $this->assertAuthenticatedAs($user, 'api');
    }

    /** @test */
    public function user_can_not_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);
        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => $password.'1',
            'company_id' => $user->company_id,
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function get_access_key_token_if_get_logged_in()
    {
        /*$user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => $password,
            'company_id' => $user->company_id,
        ]);

        $data = $response->json([
            'payload'
        ]);*/
        $data = $this->getLoggedInUser();
        if(isset($data['token'])) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }

}
