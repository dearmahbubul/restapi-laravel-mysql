<?php
/**
 * Created by Mahbubul Alam
 * User: Happy app
 * Date: 7/5/21
 * Time: 10:59 PM
 */

namespace App\Services;

use Illuminate\Validation\Factory as Validator;

class BaseRequestValidator
{
    protected $validator;
    protected $data = [];
    protected $errors = [];
    protected $rules = [];
    protected $messages = [];

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function with(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function passes()
    {
        $validator = $this->validator->make(
            $this->data,
            $this->rules,
            $this->messages
        );

        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return false;
        }
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

}
