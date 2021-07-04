<?php
/**
 * Created by Mahbubul Alam
 * User: Happy app
 * Date: 7/5/21
 * Time: 8:06 PM
 */

namespace App\Exceptions;

use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TypeError;

trait ExceptionTrait
{
    public function apiException($request, $e)
    {
        if ($this->isModel($e)) {
            return $this->modelResponse();
        } elseif ($this->isHttp($e)) {
            return $this->httpResponse();
        } elseif ($this->isTypeError($e)) {
            return $this->processErrorResponse($e);
        } elseif ($this->isSystemError($e)) {
            return $this->processErrorResponse($e);
        }
        return parent::render($request, $e);

    }

    protected function isModel($e) : bool
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isHttp($e) : bool
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function isTypeError($e) : bool
    {
        return $e instanceof TypeError;
    }

    protected function isSystemError($e) : bool
    {
        return $e instanceof ErrorException;
    }

    protected function modelResponse() : JsonResponse
    {
        return response()->json([
            'message' => 'Data not found',
            'payload' => null,
            'status'  => false,
            'status_code'  => Response::HTTP_NOT_FOUND
        ],Response::HTTP_NOT_FOUND);
    }

    protected function httpResponse() : JsonResponse
    {
        return response()->json([
            'message' => 'Requested url not found',
            'payload' => null,
            'status'  => false,
            'status_code'  => Response::HTTP_NOT_FOUND
        ],Response::HTTP_NOT_FOUND);
    }

    protected function processErrorResponse($e) : JsonResponse
    {
        return response()->json([
            'message' => $e->getMessage(),
            'payload' => null,
            'status'  => false,
            'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR
        ],Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
