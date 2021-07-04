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
use Illuminate\Testing\TestResponse;
use Tests\Feature\BaseTestHelper;
use Tests\TestCase;

class GetUserTest extends BaseTestHelper
{
    use RefreshDatabase;

    /** @test */
    public function get_user_by_access_token()
    {
        $accessToken = $this->getAccessToken();
        $response = $this->get(route('user.me'), ['HTTP_Authorization' => 'Bearer '.$accessToken]);
        $response->assertOk();
    }

}
