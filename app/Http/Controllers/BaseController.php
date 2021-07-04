<?php
/**
 * Created by Mahbubul Alam
 * User: Masakh-IT
 * Date: 4/10/21
 * Time: 2:09 AM
 */


namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /**
     * @param array $response
     * @return JsonResponse
     */
    public function returnApiResponse(array $response): JsonResponse
    {
        return response()->json($response, !empty($response['status_code']) ? $response['status_code'] : Response::HTTP_OK);
    }
}
