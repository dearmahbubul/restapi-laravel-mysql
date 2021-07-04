<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/7/21
 * Time: 6:59 PM
 */

namespace App\Services\Validation;

use App\Services\BaseRequestValidator;

class UserValidator extends BaseRequestValidator
{
    public function setLoginRules()
    {
        $this->rules = [
            'email' => 'required|min:8|max:128|email',
            'password' => 'required|min:8|max:64|string',
            'company_id' => 'required',
        ];
    }

    public function setRegistrationRules($company_id,$user_id=null)
    {
        $this->rules = array(
            'name' => 'required|string|min:1|max:64',
            'email' => 'required|max:128|email|unique:users,email,' . $user_id . ',id,company_id,' . $company_id,
//            'email' => 'required|min:8|max:128|email|unique:users,email,' . $user_id . ',id',
            'phone_number' => 'required|min:10|max:20',
            'password' => 'required|confirmed|min:8|max:64',
        );
    }

    public function setForgotPasswordRule()
    {
        $this->rules = array(
            'email' => 'required|min:8|max:128|email',
            'company_id' => 'required',
        );
    }

    public function setResetPasswordRule()
    {
        $this->rules = array(
            'company_id' => 'required',
            'email' => 'required|min:8|max:128|email',
            'token' => 'required',
            'password' => 'required|min:8|max:64|confirmed',
        );
    }

    public function setPasswordChangeRules()
    {
        $this->rules = array(
            'password' => 'required|confirmed|min:8|max:64',
        );
    }

    public function setUserUpdateRules($company_id,$user_id)
    {
        $this->rules = array(
            'company_id' => 'required',
            'name' => 'required|string|min:1|max:64',
            'email' => 'required|max:128|email|unique:users,email,' . $user_id . ',id,company_id,' . $company_id,
            'phone_number' => 'required|max:20|min:10',
        );
    }

}
