<?php
/**
 * Created by Mahbubul Alam
 * User: Happy app
 * Date: 7/4/21
 * Time: 8:06 PM
 */

namespace App\Http\Controllers\Company;


use App\Contracts\Services\UserAuthContract;
use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends BaseController
{
    private $userService;

    public function __construct(UserAuthContract $userContact)
    {
        $this->userService = $userContact;
    }


    /**
     * @OA\Post(
     * path="/api/v1/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"User RestApi"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="company_id", type="int", format="number", example="1"),
     *       @OA\Property(property="email", type="string", format="email", example="mahbubul@orangetoolz.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Correct credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "user": {
    "id": 1,
    "company_id": 1,
    "is_parent": 1,
    "name": "Mahbubul Alam",
    "email": "mahbubul@orangetoolz.com",
    "email_verified_at": "2021-07-04T09:28:02.000000Z",
    "phone_number": "01906043504",
    "profile_image": null,
    "email_signature": null,
    "logged_at": null,
    "status": 1,
    "created_at": "2021-07-04T09:28:02.000000Z",
    "updated_at": "2021-07-04T09:28:02.000000Z"
    },
    "token": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkucmFsbGUudGVzdFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE2MjQ2MzA5MDcsImV4cCI6MTYyNDYzNDUwNywibmJmIjoxNjI0NjMwOTA3LCJqdGkiOiJ6cE80VDdBVUJRbjMyRkU5Iiwic3ViIjoxLCJwcnYiOiJiOTEyNzk5NzhmMTFhYTdiYzU2NzA0ODdmZmYwMWUyMjgyNTNmZTQ4In0.egbMdLZlfz_WKyQj7rPsqn6Y0ynxHWKvhdfDABD1gXQ",
    "token_type": "bearer",
    "expires_in": 3600
    }
    }),
     *       @OA\Property(property="message", type="string", example="Successfully logged in"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="200")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "email": {
    "The email must be at least 8 characters.",
    "The email must be a valid email address."
    },
    "password": {
    "The password must be at least 8 characters."
    }
    }),
     *       @OA\Property(property="message", type="string", example="Given data is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="422")
     *        )
     *     ),
     * )
     */
    public function login(Request $request) : JsonResponse
    {

        return $this->returnApiResponse($this->userService->login($request));
    }

    /**
     * @OA\Post(
     * path="/api/v1/forget-password",
     * summary="Forget password",
     * description="Forget password request to set new password",
     * operationId="passwordReset",
     * tags={"User RestApi"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="company_id", type="int", format="number", example="1"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Valid email password reset request, this will send a link to your email to reset your password",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example="null"),
     *       @OA\Property(property="message", type="string", example="We have emailed your password reset link"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="200")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="Wrong email request response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example="null"),
     *       @OA\Property(property="message", type="string", example="User data not found"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="404")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "email": {
    "The email must be a valid email address."
    }
    }),
     *       @OA\Property(property="message", type="string", example="Given data is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="422")
     *        )
     *     ),
     * )
     */
    public function forgetPassword(Request $request) : JsonResponse
    {
        return $this->returnApiResponse($this->userService->handleForgetPassword($request));
    }

    /**
     * @OA\Post(
     * path="/api/v1/reset-password",
     * summary="Reset password",
     * description="Reset your password from the emailed link",
     * operationId="passwordReset",
     * tags={"User RestApi"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","token","password","password_confirmation"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="company_id", type="int", format="number", example="1"),
     *       @OA\Property(property="token", type="string", format="string", example="23232dsafdasdfa2323233"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Successfull password reset response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example="null"),
     *       @OA\Property(property="message", type="string", example="Your password has been reset successfully"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="201")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "token": {
    "The token field is required."
    },
    "email": {
    "The email must be a valid email address."
    },
    "password": {
    "The password confirmation does not match.",
    "The password must be at least 8 characters."
    }
    }),
     *       @OA\Property(property="message", type="string", example="Given data is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="422")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Invalid token response, User should be authorized to access this route",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Token is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * )
     */
    public function resetPassword(Request $request) : JsonResponse
    {
        return $this->returnApiResponse($this->userService->handleResetPassword($request));
    }

    /**
     * @OA\Post(
     * path="/api/v1/signup",
     * summary="Signup",
     * description="Register new user by admin. Register by name, email, password, phone_number",
     * operationId="authSignup",
     * tags={"User RestApi"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials. If company id given then need is_super property to make sub-user. If new company then need to provide comapny_name not company_id",
     *    @OA\JsonContent(
     *       required={"name","email","phone_number","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", format="string", example="Mahbubul Alam"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="company_id", type="int", format="number", example="1"),
     *       @OA\Property(property="is_super", type="int", format="number", example="0"),
     *       @OA\Property(property="company_name", type="string", format="string", example="Orangetoolz"),
     *       @OA\Property(property="phone_number", type="string", format="mobile", example="01774573275"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Successfull signup/registration response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
                "name": "Mahbubul",
                "email": "mahbubul@masakh.xyz",
                "company_id": "1",
                "is_super": "0",
                "phone_number": "01774753432",
                "updated_at": "2021-06-25T19:19:36.000000Z",
                "created_at": "2021-06-25T19:19:36.000000Z",
                "id": 2
                }),
     *       @OA\Property(property="message", type="string", example="Signup successfully completed"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="201")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
            "name": {
            "The name field is required."
            },
            "email": {
            "The email must be a valid email address."
            },
            "phone_number": {
            "The phone number must be at least 10 characters."
            },
            "password": {
            "The password confirmation does not match.",
            "The password must be at least 8 characters."
            }
            }),
     *       @OA\Property(property="message", type="string", example="Given data is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="422")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Invalid token response, User should be authorized to access this route",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Token is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * )
     */
    public function signup(Request $request) : JsonResponse
    {
        return $this->returnApiResponse($this->userService->handleSignup($request));
    }


    /**
     * @OA\Get (
     * path="/api/v1/me",
     * summary="Logged user information",
     * description="Get logged user information",
     * operationId="authInformation",
     * tags={"User RestApi"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Valid logged user response. Bearer token should be correct",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "id": 1,
    "company_id": 1,
    "is_parent": 1,
    "name": "Mahbubul Alam",
    "email": "mahbubul@orangetoolz.com",
    "email_verified_at": "2021-07-04T09:28:02.000000Z",
    "phone_number": "01906043504",
    "profile_image": null,
    "email_signature": null,
    "logged_at": null,
    "status": 1,
    "created_at": "2021-07-04T09:28:02.000000Z",
    "updated_at": "2021-07-04T09:28:02.000000Z"
            }),
     *       @OA\Property(property="message", type="string", example="Logged user fetched"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="200")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Invalid token response, User should be authorized to access this route",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Token is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * )
     */
    public function me() : JsonResponse
    {
        return $this->returnApiResponse($this->userService->getLoggedUser());
    }

    /**
     * @OA\Get (
     * path="/api/v1/logout",
     * summary="Signout",
     * description="User logged out and invlaidate token",
     * operationId="signOut",
     * tags={"User RestApi"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Logged out successfull response. Bearer token should be correct",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example="null"),
     *       @OA\Property(property="message", type="string", example="Successfully logged out from the system"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="200")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Invalid token response, User should be authorized to access this route",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Token is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * )
     */
    public function logout() : JsonResponse
    {
        return $this->returnApiResponse($this->userService->logout());
    }


    public function refresh() : JsonResponse
    {
        return $this->returnApiResponse($this->userService->getRefreshUserToken());
    }


    public function guard(): Guard
    {
        return Auth::guard();
    }

    /**
     * @OA\Put (
     * path="/api/v1/user/password/update/{id}",
     * summary="Update user password",
     * description="Update user password",
     * operationId="userPasswordUpdate",
     * tags={"User RestApi"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Update user informations",
     *    @OA\JsonContent(
     *       required={"password","password_confirmation"},
     *       @OA\Property(property="password", type="string", format="password", example="12345678"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Successfull updated password response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example="null"),
     *       @OA\Property(property="message", type="string", example="Password is successfully updated"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="201")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "password": {
    "The password confirmation does not match.",
    "The password must be at least 8 characters."
    }
    }),
     *       @OA\Property(property="message", type="string", example="Given data is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="422")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Invalid token response, User should be authorized to access this route",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Token is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="If athenticated user inspect and pushed other user id to change password then it will be a bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Access denied"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * )
     */
    public function changePassword(Request $request,$id) : JsonResponse
    {
        return $this->returnApiResponse($this->userService->changePassword($request, auth()->id(),$id));
    }

    /**
     * @OA\Put (
     * path="/api/v1/user/update/{id}",
     * summary="Update User",
     * description="Update user information like name, email, phone_number etc",
     * operationId="userUpdate",
     * tags={"User RestApi"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Update user informations",
     *    @OA\JsonContent(
     *       required={"name","email","phone_number"},
     *       @OA\Property(property="name", type="string", format="string", example="Mahbubul Alam"),
     *       @OA\Property(property="company_id", type="int", format="number", example="1"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="phone_number", type="string", format="mobile", example="01774573275")
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Successfull update response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "id": 1,
    "company_id": 1,
    "is_parent": 1,
    "name": "Mahbubul Alam",
    "email": "mahbubul@orangetoolz.com",
    "email_verified_at": "2021-07-04T09:28:02.000000Z",
    "phone_number": "01906043504",
    "profile_image": null,
    "email_signature": null,
    "logged_at": null,
    "status": 1,
    "created_at": "2021-07-04T09:28:02.000000Z",
    "updated_at": "2021-07-04T09:28:02.000000Z"
    }),
     *       @OA\Property(property="message", type="string", example="User is successfully updated"),
     *       @OA\Property(property="status", type="bool", example="true"),
     *       @OA\Property(property="status_code", type="int", example="201")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Validation error response",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="collection", example={
    "name": {
    "The name field is required."
    },
    "email": {
    "The email must be a valid email address."
    },
    "phone_number": {
    "The phone number must be at least 10 characters."
    }
    }),
     *       @OA\Property(property="message", type="string", example="Given data is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="422")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Invalid token response, User should be authorized to access this route",
     *    @OA\JsonContent(
     *       @OA\Property(property="payload", type="null", example="null"),
     *       @OA\Property(property="message", type="string", example="Token is invalid"),
     *       @OA\Property(property="status", type="bool", example="false"),
     *       @OA\Property(property="status_code", type="int", example="401")
     *        )
     *     ),
     * )
     */
    public function updateUser(Request $request, $id) : JsonResponse
    {
        return $this->returnApiResponse($this->userService->updateUser($request, auth()->id(),$id));
    }
}
