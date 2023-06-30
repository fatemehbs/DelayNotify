<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DataResponse implements Responsable
{
    public function __construct($data = [], $status = 200)
    {
        $this->data = $data;
        $this->status = $status;
    }

    public function status()
    {
        switch (strtolower($this->name)) {
            case 'teapot':
                return 418;
            default:
                return 200;
        }
    }

    public function toResponse($request): JsonResponse|Response
    {
        return response()->json($this->data, $this->status);
    }
}
