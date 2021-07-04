<?php
/**
 * Created by Mahbubul Alam
 * User: Masakh Technology
 * Date: 4/9/21
 * Time: 8:06 PM
 */

namespace Tests\Feature\User\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @dataProvider nameInputValidation
     * @dataProvider emailInputValidation
     * @dataProvider phoneNumberInputValidation
     * @dataProvider passwordInputValidation
     */
    public function is_validates_signup_credentials($form_input, $form_input_value)
    {
        $response = $this->post(route('user.signup'), [$form_input => $form_input_value]);
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

    public function passwordInputValidation() : array
    {
        return [
            ['password', ''],
            ['password', 'lorem2'],
        ];
    }

    public function passwordConfirmInputValidation() : array
    {
        return [
            ['password_confirmation', '1'],
            ['password_confirmation', 'lorem'],
        ];
    }

    /** @test */
    public function user_can_signup_with_correct_credentials()
    {
        $response = $this->post(route('user.signup'), [
            'name' => 'Baten Siloo',
            'company_name' => 'OrangeToolz',
            'email' => 'hello@gmail.com',
            'phone_number' => '2202215462',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertCreated();
    }

}
