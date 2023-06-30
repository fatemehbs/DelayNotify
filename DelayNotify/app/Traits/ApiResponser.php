<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{

    protected function successResponse($data, $code = 200, $message = null): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($code, $message=null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
