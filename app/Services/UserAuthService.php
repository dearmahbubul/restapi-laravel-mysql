<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/7/21
 * Time: 8:50 PM
 */


namespace App\Services;

use App\Contracts\Repositories\CompanyRepository;
use App\Contracts\Repositories\PasswordResetRepository;
use App\Contracts\Repositories\UserRepository;
use App\Contracts\Services\UserAuthContract;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Services\Validation\UserValidator;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserAuthService implements UserAuthContract
{
    private $userRepository;
    private $userValidator;
    private $passwordResetRepository;
    private $companyRepository;

    public function __construct(
        UserRepository $userRepository,
        PasswordResetRepository $passwordResetRepository,
        UserValidator $userValidator,
        CompanyRepository $companyRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordResetRepository = $passwordResetRepository;
        $this->userValidator = $userValidator;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param int $user_id
     * @return object
     */
    public function getByUserId(int $user_id) : object
    {
        return $this->userRepository->find($user_id);
    }

    /**
     * @uses Get logged in using credentials and get access token and user info
     * @param Request $request
     * @return array
     */
    public function login(Request $request) : array
    {
        $this->userValidator->setLoginRules();
        if(! $this->userValidator->with($request->all())->passes()) {
            return getFormattedResponseData($this->userValidator->errors(), trans('messages.validation-error'), false, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $credentials = $request->only('email', 'password','company_id');
            if ($token = $this->guard()->attempt($credentials)) {
                $tokenDetails = $this->generateTokenProperty($token);
                $payload = [
                    'user' => $this->guard()->user(),
                    'token' => $tokenDetails
                ];
                return getFormattedResponseData($payload, 'Successfully logged in', true, Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return getFormattedResponseData(null, 'Sorry, wrong email address or password. Please try again', false, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard(): Guard
    {
        return Auth::guard();
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return array
     */
    protected function generateTokenProperty(string $token) : array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ];
    }

    public function logout() : array
    {
        $this->guard()->logout();
        // Pass true to force the token to be blacklisted "forever"
//        auth()->logout(true);
        return getFormattedResponseData(null, 'Successfully logged out from the system', true, Response::HTTP_OK);
    }

    public function getLoggedUser() : array
    {
        return getFormattedResponseData($this->guard()->user(), 'Logged user fetched', true, Response::HTTP_OK);
    }

    public function getRefreshUserToken() : array
    {
        $tokenDetails = $this->generateTokenProperty($this->guard()->refresh());
        $payload = [
            'user' => $this->guard()->user(),
            'token' => $tokenDetails
        ];
        return getFormattedResponseData($payload, 'User refresh token fetched successfully', true, Response::HTTP_OK);
    }

    /**
     * Handle signup
     *
     * @param Request $request
     * @return array
     */
    public function handleSignup(Request $request): array
    {
        try {
            if(!$request->has('company_id')){
                $company = $this->companyRepository->create([
                   'name' => $request->has('company_name') ? $request->get('company_name') : 'New company'
                ]);
                $companyId = $company ? $company->id : 1;
            } else {
                $companyId = $request->get('company_id');
            }
            $this->userValidator->setRegistrationRules($companyId);
            if(! $this->userValidator->with($request->all())->passes()) {
                return getFormattedResponseData($this->userValidator->errors(), trans('messages.validation-error'), false, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $data = [
                'company_id' => $companyId,
                'is_parent' => $request->has('is_parent') ? $request->get('is_parent') : PARENT_USER,
                'name' => $request->get('name'),
                'email' => trim($request->get('email')),
                'phone_number' => trim($request->get('phone_number')),
                'password' => bcrypt($request->get('password'))
            ];
            return $this->createUser($data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return getFormattedResponseData(null, 'Failed! user not registered', false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Store a new user to database
     *
     * @param array $data
     * @return array
     */
    private function createUser(array $data) : array
    {
        try {
            $user = $this->userRepository->create($data);
            return getFormattedResponseData($user, 'Signup successfully completed', true, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return getFormattedResponseData(null, 'Failed! user not registered', false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Handle forget password
     *
     * @param Request $request
     * @return array
     */
    public function handleForgetPassword(Request $request) : array
    {
        try {
            $this->userValidator->setForgotPasswordRule();
            if(! $this->userValidator->with($request->all())->passes()) {
                return getFormattedResponseData($this->userValidator->errors(), trans('messages.validation-error'), false, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $userResponse = $this->getUserByCompanyAndEmail($request->get('company_id'),$request->get('email'));
            if ($userResponse['status'] === false) {
                return $userResponse;
            } else {
                return $this->sendPasswordResetEmail($userResponse['payload']);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return getFormattedResponseData(null, 'Failed to send reset password link', false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Fetch data by email
     *
     * @param string $email
     * @param array $select
     * @return array
     */
    public function getUserByCompanyAndEmail(int $company_id, string $email, array $select = ['*'])
    {
        try {
            $user = $this->userRepository->findWhere([
                'company_id' => $company_id,
                'email'=> $email
            ]);
            if ($user) {
                return getFormattedResponseData($user, trans('messages.found',['type'=>'User']), true, Response::HTTP_FOUND);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return getFormattedResponseData(null, trans('messages.not-found',['type'=>'User']), false, Response::HTTP_NOT_FOUND);
    }

    /**
     * Send password reset mail by creating reset token
     *
     * @param User $user
     * @return array
     */
    private function sendPasswordResetEmail(User $user) : array
    {
        $result = $this->createPasswordResetToken($user->company_id,$user->email);

        if ($result['status'] === Response::HTTP_CREATED) {

            $user->notify(new ResetPasswordNotification($user->company_id,$result['payload']->token));
            return getFormattedResponseData(null, 'We have emailed your password reset link', true, Response::HTTP_OK);
        } else {
            return $result;
        }
    }

    /**
     * Return password reset token
     *
     * @param string $email
     * @return array
     */
    private function createPasswordResetToken(int $company_id, string $email) : array
    {
        //delete existing tokens
        $this->deletePasswordResetToken($company_id, $email);

        $token = Str::random(80);;
        return $this->savePasswordResetToken($token, $company_id, $email);
    }

    /**
     * Store password reset token
     *
     * @param string $token
     * @param string $email
     * @return array
     */
    private function savePasswordResetToken(string $token, int $company_id, string $email) : array
    {
        $response = $this->passwordResetRepository->create([
            'company_id' => $company_id,
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        if ($response) {
            return getFormattedResponseData($response, 'Successfully password reset token created', true, Response::HTTP_CREATED);
        } else {
            return getFormattedResponseData($response, 'Failed to create', false, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle reset password
     *
     * @param Request $request
     * @return array
     */
    public function handleResetPassword(Request $request) : array
    {
        try {
            $this->userValidator->setResetPasswordRule();
            if(! $this->userValidator->with($request->all())->passes()) {
                return getFormattedResponseData($this->userValidator->errors(), trans('messages.validation-error'), false, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $companyId = $request->get('company_id');
            $userResponse = $this->getUserByCompanyAndEmail($companyId, $request->get('email'));

            if ($userResponse['status'] === false) {
                return $userResponse;
            } else {
                $user = $userResponse['payload'];
            }

            $result = $this->validatePasswordResetToken($request->get('token'), $companyId, $request->get('email'));

            if ($result['status'] === false) {
                return $result;
            }

            //delete token
            $this->deletePasswordResetToken($companyId, $request->get('email'));

            //reset password
            return $this->resetPassword($user, $request->get('password'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
        return getFormattedResponseData(null, 'Failed to reset password, try again', false, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Validate password reset token
     *
     * @param string $token
     * @param string $email
     * @return array
     */
    private function validatePasswordResetToken(string $token, int $company_id, string $email) : array
    {
        $response = $this->passwordResetRepository->getPasswordResetData($token, $company_id, $email);
        if ($response) {
            return getFormattedResponseData($response, 'Token found', true, Response::HTTP_FOUND);
        } else {
            return getFormattedResponseData($response, 'Invalid password reset token. Please try again', false, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * delete password reset token
     *
     * @param int $company_id
     * @param string $email
     * @return array
     * @throws \Exception
     */
    private function deletePasswordResetToken(int $company_id, string $email)
    {
        $response = $this->passwordResetRepository->deleteWhere([
            'company_id' => $company_id,
            'email' => $email
        ]);
        if ($response) {
            return getFormattedResponseData($response, 'Token is deleted successfully', true, Response::HTTP_OK);
        } else {
            return getFormattedResponseData($response, 'Failed to delete token', false, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Reset password for the user
     *
     * @param User $user
     * @param string $password
     * @return array
     */
    private function resetPassword(User $user, string $password) : array
    {
        $response = $this->passwordResetRepository->updatewhere([
            'id' => $user->id
        ], [
            'password' => bcrypt($password)
        ]);

        if ($response) {
            return getFormattedResponseData($response, 'Your password has been reset successfully', true, Response::HTTP_OK);
        } else {
            return getFormattedResponseData($response, 'Failed to reset your password', false, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param int $auth_user_id
     * @param int $user_id
     * @return array
     * @uses change user current password
     */
    public function changePassword(Request $request, int $auth_user_id, int $user_id) : array
    {
        try {
            //
            if($auth_user_id !== $user_id) {
                return getFormattedResponseData(null, trans('messages.access-denied'), false, Response::HTTP_BAD_REQUEST);
            }
            // Check data to validate
            $this->userValidator->setPasswordChangeRules();
            if (!$this->userValidator->with($request->all())->passes()) {
                return getFormattedResponseData($this->userValidator->errors(), trans('messages.validation-error'), false, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Update new password to user
            $response = $this->userRepository->update([
                'password' => bcrypt($request->get('password'))
            ], $user_id);

            // Return response message
            if($response) {
                return getFormattedResponseData(null, trans('messages.update',['type'=>'Password']), true, Response::HTTP_CREATED);
            } else {
                return getFormattedResponseData($response, trans('messages.update.error',['type'=>'Password']), false, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return getFormattedResponseData(null, trans('messages.update',['type'=>'Password']), false, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * @param Request $request
     * @param int $auth_user_id
     * @param int $user_id
     * @return array
     * @uses update user information
     */
    public function updateUser(Request $request, int $auth_user_id, int $user_id) : array
    {
        try {
            $companyId = $request->has('company_id') ? $request->get('company_id') : 1;
            // Check data to validate
            $this->userValidator->setUserUpdateRules($companyId, $user_id);
            if (!$this->userValidator->with($request->all())->passes()) {
                return getFormattedResponseData($this->userValidator->errors(), trans('messages.validation-error'), false, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Update new password to user
            $response = $this->userRepository->updateAndGetData([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone_number' => $request->get('phone_number')
            ], $user_id);

            // Return response message
            if($response) {
                return getFormattedResponseData($response, trans('messages.update',['type'=>'User']), true, Response::HTTP_CREATED);
            } else {
                return getFormattedResponseData($response, trans('messages.update.error',['type'=>'User']), false, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return getFormattedResponseData(null, trans('messages.update.error',['type'=>'User']), false, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
