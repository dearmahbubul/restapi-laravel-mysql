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

class UpdateUserTest extends BaseTestHelper
{
    use RefreshDatabase;
    /**
     * @test
     * @dataProvider nameInputValidation
     * @dataProvider emailInputValidation
     * @dataProvider phoneNumberInputValidation
     */
    public function is_validates_update_credentials($form_input, $form_input_value)
    {
        $loggedUser = $this->getLoggedInUser();
        $userId = $loggedUser['user']['id'];
        $accessToken = $loggedUser['token']['access_token'];
        $response = $this->put(route('user.update',[$userId]), [$form_input => $form_input_value],['HTTP_Authorization' => 'Bearer '.$accessToken]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($form_input,'payload');
    }

    public function nameInputValidation() : array
    {
        return [
            ['name', ''],
            ['name', null]
        ];
    }

    public function emailInputValidation() : array
    {
        return [
            ['email', ''],
            ['email', 'lorem'],
            ['email', 'lorem-ipsum']
        ];
    }

    public function phoneNumberInputValidation() : array
    {
        return [
            ['phone_number', ''],
            ['phone_number', 'lorem']
        ];
    }

    /** @test */
    public function user_can_update_with_correct_credentials()
    {
        $loggedUser = $this->getLoggedInUser();
        $userId = $loggedUser['user']['id'];
        $accessToken = $loggedUser['token']['access_token'];
        $response = $this->put(route('user.update',[$userId]), [
            'name' => 'Baten Siloo',
            "company_id" => $loggedUser['user']['company_id'],
            'email' => 'hello@gmail.com',
            'phone_number' => '2202215462'
        ],['HTTP_Authorization' => 'Bearer '.$accessToken]);
        $response->assertCreated();
    }

}
