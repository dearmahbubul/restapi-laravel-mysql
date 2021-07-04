<?php
/**
 * Created by Mahbubul Alam
 * User: Masakh Technology
 * Date: 4/9/21
 * Time: 8:06 PM
 */

namespace Tests\Feature\User\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\Feature\BaseTestHelper;
use Tests\TestCase;

class UpdateUserPasswordTest extends BaseTestHelper
{
    use RefreshDatabase;
    /**
     * @test
     * @dataProvider passwordInputValidation
     * @dataProvider passwordConfirmationInputValidation
     */
    public function is_validates_update_password_credentials($form_input, $form_input_value)
    {
        $loggedUser = $this->getLoggedInUser();
        $userId = $loggedUser['user']['id'];
        $accessToken = $loggedUser['token']['access_token'];
        $response = $this->put(route('user.update.password',[$userId]), [$form_input => $form_input_value],['HTTP_Authorization' => 'Bearer '.$accessToken]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($form_input,'payload');
    }

    public function passwordInputValidation() : array
    {
        return [
            ['password', '333'],
            ['password', 'aa'],
        ];
    }

    public function passwordConfirmationInputValidation() : array
    {
        return [
            ['password_confirmation', '22'],
            ['password_confirmation', 'lorem']
        ];
    }

    /** @test */
    public function user_can_update_password_with_correct_credentials()
    {
        $loggedUser = $this->getLoggedInUser();
        $userId = $loggedUser['user']['id'];
        $accessToken = $loggedUser['token']['access_token'];
        $response = $this->put(route('user.update.password',[$userId]), [
            'password' => '11111111',
            "password_confirmation" => '11111111'
        ],['HTTP_Authorization' => 'Bearer '.$accessToken]);
        $response->assertCreated();
    }

}
